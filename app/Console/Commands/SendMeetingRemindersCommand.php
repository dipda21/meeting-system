<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendMeetingRemindersCommand extends Command
{
    protected $signature = 'meetings:send-reminders {--hours=24 : Hours before meeting to send reminder}';
    protected $description = 'Send WhatsApp reminders for upcoming meetings';

    public function handle()
    {
        $hoursBeforeReminder = $this->option('hours');
        $reminderTime = Carbon::now()->addHours($hoursBeforeReminder);
        
        // Find meetings that need reminders
        $meetings = Meeting::with(['participants.user'])
            ->where('status', 'scheduled')
            ->whereDate('meeting_date', $reminderTime->toDateString())
            ->whereTime('start_time', '>=', $reminderTime->toTimeString())
            ->whereTime('start_time', '<=', $reminderTime->addHour()->toTimeString())
            ->get();

        if ($meetings->isEmpty()) {
            $this->info('No meetings found that need reminders.');
            return;
        }

        $whatsappService = new WhatsAppService();
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($meetings as $meeting) {
            $this->info("Processing meeting: {$meeting->title}");
            
            // Check if reminder already sent
            $participantsNeedingReminder = $meeting->participants()
                ->whereNull('reminder_sent_at')
                ->with('user')
                ->get();

            if ($participantsNeedingReminder->isEmpty()) {
                $this->info("- All participants already received reminders");
                continue;
            }

            $results = $whatsappService->sendMeetingReminder($meeting, $hoursBeforeReminder);
            
            $sent = collect($results)->where('status', 'sent')->count();
            $failed = collect($results)->where('status', 'failed')->count();
            
            $totalSent += $sent;
            $totalFailed += $failed;
            
            $this->info("- Sent: {$sent}, Failed: {$failed}");
        }

        $this->info("\n=== SUMMARY ===");
        $this->info("Total meetings processed: " . $meetings->count());
        $this->info("Total reminders sent: {$totalSent}");
        $this->info("Total failed: {$totalFailed}");
    }
}