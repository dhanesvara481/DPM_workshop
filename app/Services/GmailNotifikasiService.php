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
                'jenis_notifikasi' => $jenisNotifikasi,
                // mb_substr agar tidak potong di tengah karakter multibyte/emoji
                'judul_notif'      => mb_substr($judulNotif, 0, 100, 'UTF-8'),
                // TEXT column — tidak perlu dipotong
                'isi_pesan'        => $pesan,
                'tanggal_dibuat'   => now(),
                'tanggal_dikirim'  => now(),
            ]);
        }

        return $kirim;
    }
}