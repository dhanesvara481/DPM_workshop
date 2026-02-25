<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    private string $token;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', '');
    }

    public function sendText(string $phone, string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])
            ->timeout(15)
            ->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

            $body = $response->json();

            if (!($body['status'] ?? false)) {
                Log::warning('Fonnte gagal', [
                    'phone'  => $phone,
                    'reason' => $body['reason'] ?? 'unknown',
                ]);
                return false;
            }

            Log::info('Fonnte sukses', ['phone' => $phone]);
            return true;

        } catch (\Throwable $e) {
            Log::error('Fonnte exception', [
                'phone'   => $phone,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendBulk(array $phones, string $message): array
    {
        $results = [];
        foreach ($phones as $phone) {
            $results[$phone] = $this->sendText($phone, $message);
            usleep(500_000);
        }
        return $results;
    }
}