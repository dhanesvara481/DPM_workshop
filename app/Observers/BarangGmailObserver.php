<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\User;
use App\Services\GmailNotifikasiService;

class BarangGmailObserver
{
    private const STOK_MIN = 25;

    private static array $buffer     = [];
    private static array $registered = [];

    public function updated(Barang $barang): void
    {
        if (!$barang->isDirty('stok')) {
            return;
        }

        $id       = $barang->barang_id;
        $stokBaru = (int) $barang->stok;
        $stokLama = (int) $barang->getOriginal('stok');

        if ($stokBaru >= $stokLama) {
            return;
        }

        if (!isset(self::$buffer[$id])) {
            self::$buffer[$id] = [
                'barang'     => $barang,
                'stok_awal'  => $stokLama,
                'stok_akhir' => $stokBaru,
            ];
        } else {
            self::$buffer[$id]['stok_akhir'] = $stokBaru;
            self::$buffer[$id]['barang']     = $barang;
        }

        if (!isset(self::$registered[$id])) {
            self::$registered[$id] = true;

            app()->terminating(function () use ($id) {
                $entry = self::$buffer[$id] ?? null;
                unset(self::$buffer[$id], self::$registered[$id]);

                if (!$entry) return;

                $barang    = $entry['barang'];
                $stokAwal  = $entry['stok_awal'];
                $stokAkhir = $entry['stok_akhir'];

                if ($stokAkhir >= $stokAwal) return;

                $notif  = app(GmailNotifikasiService::class);
                $admins = User::whereIn('role', ['admin', 'staff'])
                    ->where('status', 'aktif')
                    ->whereNotNull('email')
                    ->get();

                if ($stokAkhir <= 0) {
                    $subject = 'Stok Habis: ' . $barang->nama_barang;
                    $pesan   = $this->buildPesanHabis($barang, $stokAwal);
                } elseif ($stokAkhir < self::STOK_MIN) {
                    $subject = 'Stok Menipis: ' . $barang->nama_barang;
                    $pesan   = $this->buildPesanMenipis($barang, $stokAwal, $stokAkhir);
                } else {
                    return;
                }

                foreach ($admins as $admin) {
                    $notif->kirimManual(
                        $admin->email,
                        $subject,
                        $pesan,
                        'stok',
                        $subject
                    );
                }
            });
        }
    }

    private function buildPesanMenipis(Barang $barang, int $stokAwal, int $stokAkhir): string
    {
        $berkurang = $stokAwal - $stokAkhir;

        $pesan  = "⚠️ Peringatan Stok Menipis\n\n";
        $pesan .= "{$barang->nama_barang}\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "Stok sebelum  : {$stokAwal} {$barang->satuan}\n";
        $pesan .= "Berkurang     : -{$berkurang} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : {$stokAkhir} {$barang->satuan}\n";
        $pesan .= "\nSegera lakukan pengadaan barang.\n";

        return $pesan;
    }

    private function buildPesanHabis(Barang $barang, int $stokAwal): string
    {
        $pesan  = "🚨 Stok Habis!\n\n";
        $pesan .= "{$barang->nama_barang}\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "Stok sebelum  : {$stokAwal} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : 0 {$barang->satuan}\n";
        $pesan .= "\nSegera restok sebelum transaksi terhambat!\n";

        return $pesan;
    }
}