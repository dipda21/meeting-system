<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;
    protected $token;
    protected $channel;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('whapi.base_url');
        $this->token = config('whapi.token');
        $this->channel = config('whapi.channel');
        $this->timeout = config('whapi.timeout');
    }

    /**
     * Send text message - Compatible with existing code
     */
    public function sendMessage($to, $message)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($to);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/messages/text', [
                    'to' => $formattedPhone,
                    'body' => $message,
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $formattedPhone,
                    'response' => $response->json()
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $response->json()
                ];
            }

            Log::error('Failed to send WhatsApp message', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send message: ' . $response->body(),
                'error' => $response->body()
            ];
            
        } catch (\Exception $e) {
            Log::error('WhatsApp API error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'API error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk message to multiple numbers
     */
    public function sendBulkMessages($recipients, $message)
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $result = $this->sendMessage($recipient['phone'], $message);
            $results[] = [
                'phone' => $recipient['phone'],
                'name' => $recipient['name'] ?? 'Unknown',
                'status' => $result['success'] ? 'sent' : 'failed',
                'response' => $result
            ];
            
            // Delay to avoid rate limiting
            sleep(1);
        }
        
        return $results;
    }

    /**
     * Send reminder to meeting participants
     */
    public function sendMeetingReminder(\App\Models\Meeting $meeting, $hoursBeforeReminder = 24)
    {
        $participants = $meeting->participants()->with('user')->get();
        $results = [];
        
        foreach ($participants as $participant) {
            if ($participant->user && $participant->user->phone_number) {
                $reminderMessage = $this->createReminderMessage($meeting, $participant->user, $hoursBeforeReminder);
                $result = $this->sendMessage($participant->user->phone_number, $reminderMessage);
                
                $results[] = [
                    'user_id' => $participant->user->id,
                    'name' => $participant->user->name,
                    'phone' => $participant->user->phone_number,
                    'status' => $result['success'] ? 'sent' : 'failed',
                    'response' => $result
                ];
                
                // Update reminder sent timestamp if successful
                if ($result['success']) {
                    $participant->update(['reminder_sent_at' => now()]);
                }
                
                sleep(1); // Rate limiting
            }
        }
        
        return $results;
    }

    /**
     * Create reminder message
     */
    private function createReminderMessage(\App\Models\Meeting $meeting, \App\Models\User $user, $hoursBeforeReminder)
    {
        $meetingDate = \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y');
        $startTime = \Carbon\Carbon::parse($meeting->start_time)->format('H:i');
        $endTime = \Carbon\Carbon::parse($meeting->end_time)->format('H:i');
        
        return "⏰ *PENGINGAT MEETING*\n\n" .
               "Halo {$user->name},\n\n" .
               "Meeting Anda akan dimulai dalam {$hoursBeforeReminder} jam:\n\n" .
               "📋 *Judul:* {$meeting->title}\n" .
               "📅 *Tanggal:* {$meetingDate}\n" .
               "⏰ *Waktu:* {$startTime} - {$endTime}\n" .
               "📍 *Lokasi:* {$meeting->location}\n" .
               "📌 *Agenda:* {$meeting->agenda}\n\n" .
               "Mohon bersiap dan hadir tepat waktu.\n\n" .
               "Terima kasih! 🙏";
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present (Indonesia +62)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Get account info
     */
    public function getAccountInfo()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                ])
                ->get($this->baseUrl . '/account');

            return $response->successful() ? $response->json() : false;
        } catch (\Exception $e) {
            Log::error('WhatsApp API account info error: ' . $e->getMessage());
            return false;
        }
    }
    /**
 * Verify webhook signature
 */
public function verifyWebhook($payload, $signature)
{
    if (!$signature) {
        return true; // Skip verification if no signature (for testing)
    }

    $webhookSecret = config('whapi.webhook_secret');
    
    if (!$webhookSecret) {
        Log::warning('Webhook secret not configured');
        return true; // Allow if secret not configured
    }

    $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);
    
    return hash_equals($expectedSignature, $signature);
}

/**
 * Handle incoming webhook data
 */
public function handleWebhook($data)
{
    Log::info('Processing WhatsApp webhook data', $data);

    try {
        // Handle different types of webhook events based on WHAPI format
        
        // Handle message status updates
        if (isset($data['messages'])) {
            $this->handleIncomingMessages($data['messages']);
        }

        // Handle message status changes
        if (isset($data['statuses'])) {
            $this->handleStatusUpdates($data['statuses']);
        }

        // Handle ack (message acknowledgments)
        if (isset($data['ack'])) {
            $this->handleMessageAck($data['ack']);
        }

        // Handle specific WHAPI webhook events
        if (isset($data['event'])) {
            $this->handleWebhookEvent($data);
        }

        return true;

    } catch (\Exception $e) {
        Log::error('Error processing webhook data: ' . $e->getMessage(), [
            'data' => $data,
            'error' => $e->getTraceAsString()
        ]);
        return false;
    }
}

/**
 * Handle incoming messages
 */
private function handleIncomingMessages($messages)
{
    foreach ($messages as $message) {
        Log::info('Processing incoming message', $message);

        $from = $message['from'] ?? null;
        $messageId = $message['id'] ?? null;
        $messageType = $message['type'] ?? 'unknown';
        $timestamp = $message['timestamp'] ?? now();

        // Handle different message types
        switch ($messageType) {
            case 'text':
                $body = $message['text']['body'] ?? '';
                $this->processTextMessage($from, $body, $messageId, $timestamp);
                break;
                
            case 'image':
                $this->processImageMessage($from, $message, $messageId, $timestamp);
                break;
                
            case 'document':
                $this->processDocumentMessage($from, $message, $messageId, $timestamp);
                break;
                
            default:
                Log::info('Unhandled message type: ' . $messageType, $message);
        }
    }
}

/**
 * Handle message status updates
 */
private function handleStatusUpdates($statuses)
{
    foreach ($statuses as $status) {
        Log::info('Processing status update', $status);

        $messageId = $status['id'] ?? null;
        $newStatus = $status['status'] ?? 'unknown';
        $timestamp = $status['timestamp'] ?? now();
        $recipient = $status['recipient_id'] ?? null;

        // Update message status in database if you have a messages table
        // Example:
        // \App\Models\WhatsAppMessage::where('message_id', $messageId)
        //     ->update([
        //         'status' => $newStatus,
        //         'status_updated_at' => $timestamp
        //     ]);

        Log::info("Message {$messageId} status updated to: {$newStatus}");
    }
}

/**
 * Handle message acknowledgments (WHAPI specific)
 */
private function handleMessageAck($ackData)
{
    Log::info('Processing message ack', $ackData);

    $messageId = $ackData['id'] ?? null;
    $ack = $ackData['ack'] ?? null;
    
    // WHAPI ack values:
    // 1 = sent
    // 2 = delivered  
    // 3 = read
    
    $statusMap = [
        1 => 'sent',
        2 => 'delivered', 
        3 => 'read'
    ];
    
    $status = $statusMap[$ack] ?? 'unknown';
    
    Log::info("Message {$messageId} ack updated to: {$status}");
}

/**
 * Handle webhook events
 */
private function handleWebhookEvent($data)
{
    $event = $data['event'] ?? null;
    
    switch ($event) {
        case 'message':
            if (isset($data['payload'])) {
                $this->handleIncomingMessages([$data['payload']]);
            }
            break;
            
        case 'ack':
            if (isset($data['payload'])) {
                $this->handleMessageAck($data['payload']);
            }
            break;
            
        default:
            Log::info('Unhandled webhook event: ' . $event, $data);
    }
}

/**
 * Process text message
 */
private function processTextMessage($from, $body, $messageId, $timestamp)
{
    Log::info('Processing text message', [
        'from' => $from,
        'body' => $body,
        'message_id' => $messageId
    ]);

    // Auto-reply logic (optional)
    if (config('whapi.auto_reply_enabled', false)) {
        $this->handleAutoReply($from, $body);
    }

    // Save to database if needed
    // $this->saveIncomingMessage($from, $body, 'text', $messageId, $timestamp);
}

/**
 * Process image message
 */
private function processImageMessage($from, $message, $messageId, $timestamp)
{
    Log::info('Processing image message', [
        'from' => $from,
        'message_id' => $messageId
    ]);

    // Handle image message
    // You can download the image, save metadata, etc.
}

/**
 * Process document message
 */
private function processDocumentMessage($from, $message, $messageId, $timestamp)
{
    Log::info('Processing document message', [
        'from' => $from,
        'message_id' => $messageId
    ]);

    // Handle document message
}

/**
 * Handle auto-reply (optional)
 */
private function handleAutoReply($from, $body)
{
    // Simple auto-reply logic
    $lowerBody = strtolower(trim($body));
    
    $autoReplies = [
        'halo' => 'Halo! Terima kasih telah menghubungi kami.',
        'info' => 'Untuk informasi lebih lanjut, silakan hubungi admin.',
        'meeting' => 'Untuk informasi meeting, silakan cek jadwal di sistem.'
    ];

    foreach ($autoReplies as $keyword => $reply) {
        if (strpos($lowerBody, $keyword) !== false) {
            $this->sendMessage($from, $reply);
            break;
        }
    }
}

/**
 * Test webhook connection
 */
public function testWebhook()
{
    try {
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])
            ->get($this->baseUrl . '/webhooks');

        return $response->successful() ? $response->json() : false;
    } catch (\Exception $e) {
        Log::error('WhatsApp webhook test error: ' . $e->getMessage());
        return false;
    }
}
}