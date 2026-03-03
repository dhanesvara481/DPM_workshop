<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\User;
use App\Services\GmailNotifikasiService;

class BarangGmailObserver
{
    private const STOK_MIN = 25;

    public function updated(Barang $barang): void
    {
        // Hanya proses jika kolom stok yang berubah
        if (!$barang->isDirty('stok')) {
            return;
        }

        $stokBaru = (int) $barang->stok;
        $stokLama = (int) $barang->getOriginal('stok');

        // Hanya proses jika stok BERKURANG
        if ($stokBaru >= $stokLama) {
            return;
        }

        $notif  = app(GmailNotifikasiService::class);

        $admins = User::whereIn('role',['admin', 'staff'])
            ->where('status', 'aktif')
            ->whereNotNull('email')
            ->get();

        // Kasus 1: Stok habis
        if ($stokBaru <= 0 && $stokLama > 0) {
            $pesan = $this->buildPesanHabis($barang, $stokLama);

            foreach ($admins as $admin) {
                $notif->kirimManual(
                    $admin->email,
                    'Stok Habis: ' . $barang->nama_barang,
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

            foreach ($admins as $admin) {
                $notif->kirimManual(
                    $admin->email,
                    'Stok Menipis: ' . $barang->nama_barang,
                    $pesan,
                    'stok',
                    'Stok Menipis: ' . $barang->nama_barang
                );
            }

            return;
        }
    }

    private function buildPesanMenipis(Barang $barang, int $stokLama, int $stokBaru): string
    {
        $berkurang = $stokLama - $stokBaru;

        $pesan  = "⚠️ Peringatan Stok Menipis\n\n";
        $pesan .= "{$barang->nama_barang}\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "Stok sebelum  : {$stokLama} {$barang->satuan}\n";
        $pesan .= "Berkurang     : -{$berkurang} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : {$stokBaru} {$barang->satuan}\n";
        $pesan .= "\nSegera lakukan pengadaan barang.\n";

        return $pesan;
    }

    private function buildPesanHabis(Barang $barang, int $stokLama): string
    {
        $pesan  = "🚨 Stok Habis!\n\n";
        $pesan .= "{$barang->nama_barang}\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "Stok sebelum  : {$stokLama} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : 0 {$barang->satuan}\n";
        $pesan .= "\nSegera restok sebelum transaksi terhambat!\n";

        return $pesan;
    }
}