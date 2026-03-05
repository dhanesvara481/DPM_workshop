<?php

namespace App\Services;

use App\Models\Notifikasi;

class WahaNotifikasiService
{
    protected WahaService $waha;

    public function __construct(WahaService $waha)
    {
        $this->waha = $waha;
    }

    public function kirimManual(
        string $nomorHp,
        string $pesan,
        string $jenisNotifikasi,
        string $judulNotif
    ): bool {
        // Hanya kirim WA, simpan notifikasi dilakukan di Observer (1x)
        return $this->waha->sendText($nomorHp, $pesan);
    }
}