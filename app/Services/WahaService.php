<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WahaService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $session;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.waha.base_url', 'http://localhost:3000'), '/');
        $this->apiKey  = config('services.waha.api_key', '');
        $this->session = config('services.waha.session', 'default');
    }

    // ── Kirim pesan teks biasa ────────────────────────────────────────────────

    public function sendText(string $nomorHp, string $pesan): bool
    {
        $chatId = $this->formatChatId($nomorHp);

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(15)
                ->post("{$this->baseUrl}/api/sendText", [
                    'session' => $this->session,
                    'chatId'  => $chatId,
                    'text'    => $pesan,
                ]);

            if ($response->successful()) {
                Log::info('WAHA sendText sukses', ['to' => $chatId]);
                return true;
            }

            Log::warning('WAHA sendText gagal', [
                'to'     => $chatId,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return false;

        } catch (\Throwable $e) {
            Log::error('WAHA sendText exception', [
                'to'    => $chatId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    // ── Cek status session ────────────────────────────────────────────────────

    public function sessionStatus(): ?array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->baseUrl}/api/sessions/{$this->session}");

            return $response->successful() ? $response->json() : null;

        } catch (\Throwable $e) {
            Log::error('WAHA sessionStatus exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ── Start session ─────────────────────────────────────────────────────────

    public function startSession(): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->post("{$this->baseUrl}/api/sessions/start", [
                    'name' => $this->session,
                ]);

            return $response->successful();

        } catch (\Throwable $e) {
            Log::error('WAHA startSession exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // ── Ambil QR code (untuk scan pertama kali) ───────────────────────────────

    public function getQrCode(): ?string
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->baseUrl}/api/{$this->session}/auth/qr");

            if ($response->successful()) {
                $data = $response->json();
                // WAHA mengembalikan QR sebagai base64 image
                return $data['data'] ?? null;
            }

            return null;

        } catch (\Throwable $e) {
            Log::error('WAHA getQrCode exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ── Helper: format nomor HP ke chatId WhatsApp ────────────────────────────
    // Input: 08xx, +62xx, 62xx → Output: 62xxxxxxxx@c.us

    public function formatChatId(string $nomor): string
    {
        // Bersihkan semua non-digit
        $nomor = preg_replace('/\D/', '', $nomor);

        // Ganti awalan 0 dengan 62
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        // Pastikan sudah ada kode negara
        if (!str_starts_with($nomor, '62')) {
            $nomor = '62' . $nomor;
        }

        return $nomor . '@c.us';
    }

    // ── Helper: HTTP headers ──────────────────────────────────────────────────

    protected function headers(): array
    {
        $headers = ['Content-Type' => 'application/json'];

        if (!empty($this->apiKey)) {
            $headers['X-Api-Key'] = $this->apiKey;
        }

        return $headers;
    }
}