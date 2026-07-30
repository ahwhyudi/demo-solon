<?php

namespace App\Services\Wa;

use Illuminate\Support\Facades\Http;

class SendTextWaServis
{
    public function execute(string | int $phone, string $message)
    {
        $baseUrl = config('app.wa.url');
        $apiKey  = config("app.wa.api_key");

        $payload = [
            'to'      => $this->formatPhoneNumber($phone),
            'message' => $message,
        ];

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
        ])
            ->post("$baseUrl/whatsapp/send-text", $payload);

        if ($response->failed()) {
            throw new \RuntimeException(
                'WA send-text gagal: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $data = $response->json();
    }

    private function formatPhoneNumber(string | int $phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . ltrim($phoneNumber, '0');
        }

        return $phoneNumber . '@s.whatsapp.net';
    }
}
