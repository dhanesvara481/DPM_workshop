<?php

namespace App\Services;

use App\Models\Notifikasi;

class GmailNotifikasiService
{
    protected GmailService $gmail;

    public function __construct(GmailService $gmail)
    {
        $this->gmail = $gmail;
    }

    /**
     * Mirip kirimManual di WA, tapi target = email.
     */
    public function kirimManual(
        string $targetEmail,
        string $subject,
        string $pesan,
        string $jenisNotifikasi,
        string $judulNotif
    ): bool {
        return $this->gmail->sendText($targetEmail, $subject, $pesan);
    }
}