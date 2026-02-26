<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    private string $token;
    private string $url;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
        $this->url   = 'https://api.fonnte.com/send';
    }

    public function sendText(string $target, string $message): bool
    {
        // Normalisasi nomor
        $target = $this->formatNomor($target);

        $response = Http::withHeaders([
            'Authorization' => $this->token
        ])->asForm()->post($this->url, [
            'target'  => $target,
            'message' => $message,
        ]);

        return $response->successful();
    }

    private function formatNomor($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}