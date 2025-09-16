<?php
// app/Http/Controllers/WhatsAppWebhookController.php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Verify webhook (GET request)
     */
    public function verify(Request $request)
    {
        $verifyToken = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');
        $mode = $request->get('hub_mode');

        if ($mode === 'subscribe' && $verifyToken === config('whatsapp.webhook_verify_token')) {
            Log::info('WhatsApp webhook verified successfully');
            return response($challenge, 200);
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode' => $mode,
            'token' => $verifyToken
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle webhook (POST request)
     */
    public function handle(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('X-Hub-Signature-256');

            // Verify webhook signature
            if (!$this->whatsappService->verifyWebhook($payload, $signature)) {
                Log::warning('WhatsApp webhook signature verification failed');
                return response('Unauthorized', 401);
            }

            $data = $request->all();
            
            Log::info('WhatsApp webhook received', ['data' => $data]);

            // Process the webhook data
            $this->whatsappService->handleWebhook($data);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook handling error: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }
}