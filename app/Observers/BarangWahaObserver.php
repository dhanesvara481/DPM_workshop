<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\Notifikasi;
use App\Models\User;
use App\Services\WahaNotifikasiService;

class BarangWahaObserver
{
    private const STOK_MIN = 25;

    // Buffer in-memory per request: ['barang_id' => ['stokAwal' => x, 'stokAkhir' => y]]
    private static array $buffer = [];
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

        // Simpan ke buffer — catat stok AWAL pertama kali, stok AKHIR selalu update
        if (!isset(self::$buffer[$id])) {
            self::$buffer[$id] = [
                'barang'    => $barang,
                'stok_awal' => $stokLama,   // snapshot pertama
                'stok_akhir'=> $stokBaru,
            ];
        } else {
            // Update stok akhir saja (stok_awal tetap yang pertama)
            self::$buffer[$id]['stok_akhir'] = $stokBaru;
            self::$buffer[$id]['barang']     = $barang; // refresh instance
        }

        // Daftarkan terminating handler hanya sekali per barang per request
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

                $notif = app(WahaNotifikasiService::class);
                $users = User::whereIn('role', ['admin', 'staff'])
                    ->where('status', 'aktif')
                    ->whereNotNull('kontak')
                    ->get();

                if ($stokAkhir <= 0) {
                    $pesan = $this->buildPesanHabis($barang, $stokAwal);
                    $judul = 'Stok Habis: ' . $barang->nama_barang;
                } elseif ($stokAkhir < self::STOK_MIN) {
                    $pesan = $this->buildPesanMenipis($barang, $stokAwal, $stokAkhir);
                    $judul = 'Stok Menipis: ' . $barang->nama_barang;
                } else {
                    return; // stok masih aman setelah semua transaksi
                }

                foreach ($users as $user) {
                    $notif->kirimManual($user->kontak, $pesan, 'stok', $judul);
                }

                if ($users->isNotEmpty()) {
                    Notifikasi::create([
                        'jenis_notifikasi' => 'stok',
                        'judul_notif'      => mb_substr($judul, 0, 100, 'UTF-8'),
                        'isi_pesan'        => $pesan,
                        'tanggal_dibuat'   => now(),
                        'tanggal_dikirim'  => now(),
                    ]);
                }
            });
        }
    }

    private function buildPesanMenipis(Barang $barang, int $stokAwal, int $stokAkhir): string
    {
        $berkurang = $stokAwal - $stokAkhir;

        return "⚠️ *PERINGATAN STOK MENIPIS*\n\n"
            . "*{$barang->nama_barang}*\n"
            . "Kode   : {$barang->kode_barang}\n"
            . "Satuan : {$barang->satuan}\n"
            . "Stok sebelum  : {$stokAwal} {$barang->satuan}\n"
            . "Berkurang     : -{$berkurang} {$barang->satuan}\n"   // total keluar
            . "Stok sekarang : {$stokAkhir} {$barang->satuan}\n\n"
            . "Segera lakukan pengadaan barang.\n"
            . "_DPM Workshop_";
    }

    private function buildPesanHabis(Barang $barang, int $stokAwal): string
    {
        return "🚨 *STOK HABIS!*\n\n"
            . "*{$barang->nama_barang}*\n"
            . "Kode   : {$barang->kode_barang}\n"
            . "Satuan : {$barang->satuan}\n"
            . "Stok sebelum  : {$stokAwal} {$barang->satuan}\n"
            . "Stok sekarang : 0 {$barang->satuan}\n\n"
            . "Segera restok sebelum transaksi terhambat!\n"
            . "_DPM Workshop_";
    }
}