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
        $kirim = $this->gmail->sendText($targetEmail, $subject, $pesan);

        if ($kirim) {
            Notifikasi::create([
                'jenis_notifikasi' => $jenisNotifikasi, // contoh: 'jadwal' / 'stok'
                'judul_notif'      => $judulNotif,
                'isi_pesan'        => substr($pesan, 0, 150),
                'tanggal_dibuat'   => now(),
                'tanggal_dikirim'  => now(),
            ]);
        }

        return $kirim;
    }
}