<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMeetingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $meeting;
    protected $user;
    protected $notificationType;
    protected $additionalData;

    public function __construct(Meeting $meeting, User $user, $notificationType = 'invitation', $additionalData = [])
    {
        $this->meeting = $meeting;
        $this->user = $user;
        $this->notificationType = $notificationType;
        $this->additionalData = $additionalData;
    }

    public function handle(WhatsAppService $whatsappService)
    {
        try {
            switch ($this->notificationType) {
                case 'invitation':
                    $result = $whatsappService->sendMeetingInvitation($this->meeting, $this->user);
                    break;
                    
                case 'reminder':
                    $timeRemaining = $this->additionalData['time_remaining'] ?? '15 menit';
                    $result = $whatsappService->sendMeetingReminder($this->meeting, $this->user, $timeRemaining);
                    break;
                    
                case 'status_update':
                    $status = $this->additionalData['status'] ?? 'updated';
                    $result = $whatsappService->sendStatusUpdate($this->meeting, $this->user, $status);
                    break;
                    
                case 'cancellation':
                    $result = $whatsappService->sendCancellation($this->meeting, $this->user);
                    break;
                    
                default:
                    Log::warning('Unknown notification type: ' . $this->notificationType);
                    return;
            }

            if ($result['success']) {
                Log::info('WhatsApp notification sent successfully', [
                    'type' => $this->notificationType,
                    'meeting' => $this->meeting->title,
                    'user' => $this->user->name,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                Log::error('WhatsApp notification failed', [
                    'type' => $this->notificationType,
                    'meeting' => $this->meeting->title,
                    'user' => $this->user->name,
                    'error' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp notification job failed', [
                'type' => $this->notificationType,
                'meeting' => $this->meeting->title,
                'user' => $this->user->name,
                'error' => $e->getMessage()
            ]);
            
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('WhatsApp notification job permanently failed', [
            'type' => $this->notificationType,
            'meeting' => $this->meeting->title,
            'user' => $this->user->name,
            'error' => $exception->getMessage()
        ]);
    }
}