<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Services\FonnteService;

class JadwalNotifikasiService
{
    protected FonnteService $wa;

    public function __construct(FonnteService $wa)
    {
        $this->wa = $wa;
    }

    public function kirimManual(string $target, string $pesan, string $judul)
    {
        $kirim = $this->wa->sendText($target, $pesan);

        if ($kirim) {
            Notifikasi::create([
                'jenis_notifikasi' => 'jadwal',
                'judul_notif'      => $judul,
                'isi_pesan'        => substr($pesan, 0, 150),
                'tanggal_dibuat'   => now(),
                'tanggal_dikirim'  => now(),
            ]);
        }

        return $kirim;
    }
}