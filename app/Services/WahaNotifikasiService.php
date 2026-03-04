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

    /**
     * Kirim pesan WhatsApp dan simpan log ke tabel notifikasi.
     *
     * @param  string  $nomorHp        Nomor HP tujuan (format bebas: 08xx / +62xx)
     * @param  string  $pesan          Isi pesan yang dikirim
     * @param  string  $jenisNotifikasi  Kategori: jadwal / stok / invoice / info
     * @param  string  $judulNotif     Label singkat untuk log notifikasi
     */
    public function kirimManual(
        string $nomorHp,
        string $pesan,
        string $jenisNotifikasi,
        string $judulNotif
    ): bool {
        $kirim = $this->waha->sendText($nomorHp, $pesan);

        if ($kirim) {
            Notifikasi::create([
                'jenis_notifikasi' => $jenisNotifikasi,
                'judul_notif'      => substr($judulNotif, 0, 30),
                'isi_pesan'        => substr($pesan, 0, 150),
                'tanggal_dibuat'   => now(),
                'tanggal_dikirim'  => now(),
            ]);
        }

        return $kirim;
    }
}