<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\User;
use App\Services\WahaNotifikasiService;

class BarangWahaObserver
{
    private const STOK_MIN = 25;

    public function updated(Barang $barang): void
    {
        if (!$barang->isDirty('stok')) {
            return;
        }

        $stokBaru = (int) $barang->stok;
        $stokLama = (int) $barang->getOriginal('stok');

        if ($stokBaru >= $stokLama) {
            return;
        }

        $notif = app(WahaNotifikasiService::class);

        $users = User::whereIn('role', ['admin', 'staff'])
            ->where('status', 'aktif')
            ->whereNotNull('kontak')
            ->get();

        // Kasus 1: Stok habis
        if ($stokBaru <= 0 && $stokLama > 0) {
            $pesan = $this->buildPesanHabis($barang, $stokLama);

            foreach ($users as $user) {
                $notif->kirimManual(
                    $user->kontak,
                    $pesan,
                    'stok',
                    'Stok Habis: ' . $barang->nama_barang
                );
            }

            return;
        }

        // Kasus 2: Stok menipis
        if ($stokBaru > 0 && $stokBaru < self::STOK_MIN) {
            $pesan = $this->buildPesanMenipis($barang, $stokLama, $stokBaru);

            foreach ($users as $user) {
                $notif->kirimManual(
                    $user->kontak,
                    $pesan,
                    'stok',
                    'Stok Menipis: ' . $barang->nama_barang
                );
            }
        }
    }

    private function buildPesanMenipis(Barang $barang, int $stokLama, int $stokBaru): string
    {
        $berkurang = $stokLama - $stokBaru;

        return "⚠️ *PERINGATAN STOK MENIPIS*\n\n"
            . "*{$barang->nama_barang}*\n"
            . "Kode   : {$barang->kode_barang}\n"
            . "Satuan : {$barang->satuan}\n"
            . "Stok sebelum  : {$stokLama} {$barang->satuan}\n"
            . "Berkurang     : -{$berkurang} {$barang->satuan}\n"
            . "Stok sekarang : {$stokBaru} {$barang->satuan}\n\n"
            . "Segera lakukan pengadaan barang.\n"
            . "_DPM Workshop_";
    }

    private function buildPesanHabis(Barang $barang, int $stokLama): string
    {
        return "🚨 *STOK HABIS!*\n\n"
            . "*{$barang->nama_barang}*\n"
            . "Kode   : {$barang->kode_barang}\n"
            . "Satuan : {$barang->satuan}\n"
            . "Stok sebelum  : {$stokLama} {$barang->satuan}\n"
            . "Stok sekarang : 0 {$barang->satuan}\n\n"
            . "Segera restok sebelum transaksi terhambat!\n"
            . "_DPM Workshop_";
    }
}