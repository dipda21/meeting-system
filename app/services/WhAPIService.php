<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhAPIService
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
     * Send text message
     */
    public function sendMessage($to, $message)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/messages/text', [
                    'to' => $to,
                    'body' => $message,
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return $response->json();
            }

            Log::error('Failed to send WhatsApp message', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp API error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send meeting reminder message
     */
    public function sendMeetingReminder($to, $meetingData)
    {
        $message = $this->formatMeetingMessage($meetingData);
        return $this->sendMessage($to, $message);
    }

    /**
     * Send bulk meeting reminders
     */
    public function sendBulkMeetingReminders($participants, $meetingData)
    {
        $results = [];
        
        foreach ($participants as $participant) {
            $phoneNumber = $this->formatPhoneNumber($participant['phone']);
            $personalizedData = array_merge($meetingData, [
                'participant_name' => $participant['name'] ?? 'Peserta'
            ]);
            
            $result = $this->sendMeetingReminder($phoneNumber, $personalizedData);
            $results[] = [
                'phone' => $phoneNumber,
                'name' => $participant['name'] ?? 'Unknown',
                'status' => $result ? 'sent' : 'failed'
            ];
            
            // Delay to avoid rate limiting
            sleep(1);
        }
        
        return $results;
    }

    /**
     * Format meeting message
     */
    private function formatMeetingMessage($data)
    {
        $template = "🏢 *PENGINGAT RAPAT*\n\n";
        $template .= "Halo {participant_name},\n\n";
        $template .= "📅 *Tanggal:* {date}\n";
        $template .= "⏰ *Waktu:* {time}\n";
        $template .= "📍 *Tempat:* {location}\n";
        $template .= "📋 *Agenda:* {agenda}\n\n";
        
        if (isset($data['meeting_link'])) {
            $template .= "🔗 *Link Meeting:* {meeting_link}\n\n";
        }
        
        $template .= "Mohon hadir tepat waktu.\n";
        $template .= "Terima kasih! 🙏";

        // Replace placeholders
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
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
}