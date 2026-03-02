<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\User;
use App\Models\Notifikasi;
// use App\Services\FonnteService;

class BarangObserver
{
    private const STOK_MIN = 25;

    public function updated(Barang $barang)
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

        // $wa     = app(FonnteService::class);
        $admins = User::whereIn('role', ['admin', 'staff'])
                      ->where('status', 'aktif')
                      ->whereNotNull('kontak')
                      ->get();

        // ── Kasus 1: Stok HABIS (0) ──────────────────────────────────────────
        if ($stokBaru <= 0 && $stokLama > 0) {
            $pesan = $this->buildPesanHabis($barang, $stokLama);

            foreach ($admins as $admin) {
                $ok = $wa->sendText($admin->kontak, $pesan);

                if ($ok) {
                    Notifikasi::create([
                        'jenis_notifikasi' => 'stok',
                        'judul_notif'      => 'Stok Habis: ' . $barang->nama_barang,
                        'isi_pesan'        => substr($pesan, 0, 150),
                        'tanggal_dibuat'   => now(),
                        'tanggal_dikirim'  => now(),
                    ]);
                }
            }

            return;
        }

        // ── Kasus 2: Stok MENIPIS (1 s/d 24) ────────────────────────────────
        if ($stokBaru > 0 && $stokBaru < self::STOK_MIN) {
            $pesan = $this->buildPesanMenipis($barang, $stokLama, $stokBaru);

            foreach ($admins as $admin) {
                $ok = $wa->sendText($admin->kontak, $pesan);

                if ($ok) {
                    Notifikasi::create([
                        'jenis_notifikasi' => 'stok',
                        'judul_notif'      => 'Stok Menipis: ' . $barang->nama_barang,
                        'isi_pesan'        => substr($pesan, 0, 150),
                        'tanggal_dibuat'   => now(),
                        'tanggal_dikirim'  => now(),
                    ]);
                }
            }

            return;
        }
    }

    // ── Builder pesan stok MENIPIS ────────────────────────────────────────────
    private function buildPesanMenipis(Barang $barang, int $stokLama, int $stokBaru): string
    {
        $berkurang = $stokLama - $stokBaru;

        $pesan  = "⚠️ *Peringatan Stok Menipis*\n\n";
        $pesan .= "📦 *{$barang->nama_barang}*\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "──────────────────\n";
        $pesan .= "Stok sebelum  : {$stokLama} {$barang->satuan}\n";
        $pesan .= "Berkurang     : -{$berkurang} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : *{$stokBaru} {$barang->satuan}*\n";
        $pesan .= "──────────────────\n";
        $pesan .= "🔔 Segera lakukan pengadaan barang.\n";
        $pesan .= "_DPM Workshop – " . now('Asia/Makassar')->format('d/m/Y H:i') . "_";

        return $pesan;
    }

    // ── Builder pesan stok HABIS ──────────────────────────────────────────────
    private function buildPesanHabis(Barang $barang, int $stokLama): string
    {
        $pesan  = "🚨 *Stok Habis!*\n\n";
        $pesan .= "📦 *{$barang->nama_barang}*\n";
        $pesan .= "Kode   : {$barang->kode_barang}\n";
        $pesan .= "Satuan : {$barang->satuan}\n";
        $pesan .= "──────────────────\n";
        $pesan .= "Stok sebelum  : {$stokLama} {$barang->satuan}\n";
        $pesan .= "Stok sekarang : *0 {$barang->satuan}* ❌\n";
        $pesan .= "──────────────────\n";
        $pesan .= "⚡ Segera restok sebelum transaksi terhambat!\n";
        $pesan .= "_DPM Workshop – " . now('Asia/Makassar')->format('d/m/Y H:i') . "_";

        return $pesan;
    }
}