<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class TestWhatsAppCommand extends Command
{
    protected $signature = 'whatsapp:test {phone?}';
    protected $description = 'Test WhatsApp connection';

    public function handle()
    {
        $whatsappService = new WhatsAppService();
        
        // Test basic connection
        $this->info('Testing WhatsApp connection...');
        
        if ($phone = $this->argument('phone')) {
            $testMessage = "🧪 *TEST MESSAGE*\n\n" .
                          "Ini adalah pesan test dari sistem meeting.\n\n" .
                          "Waktu: " . now()->format('d/m/Y H:i:s') . "\n\n" .
                          "Jika Anda menerima pesan ini, artinya koneksi WhatsApp berhasil! ✅";
            
            $this->info("Sending test message to {$phone}...");
            $result = $whatsappService->sendMessage($phone, $testMessage);
            
            if ($result['success']) {
                $this->info('✅ Test message sent successfully!');
                $this->info('Response: ' . json_encode($result['data'], JSON_PRETTY_PRINT));
            } else {
                $this->error('❌ Failed to send test message!');
                $this->error('Error: ' . $result['message']);
            }
        } else {
            $this->info('Add phone number as argument to send test message:');
            $this->info('php artisan whatsapp:test 6287811081945');
        }
    }
}