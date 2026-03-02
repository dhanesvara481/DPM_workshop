<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\Notifikasi;
use App\Models\User;
// use App\Services\FonnteService;
use Illuminate\Console\Command;

class NotifikasiStokMenipis extends Command
{
    protected $signature   = 'notifikasi:stok-menipis';
    protected $description = 'Kirim notifikasi stok menipis ke admin & staff';

    public function handle(FonnteService $wa): int
    {
        $threshold = (int) env('STOK_MIN_THRESHOLD', 25);

        $barangs = Barang::where('stok', '<', $threshold)->get();

        if ($barangs->isEmpty()) {
            return self::SUCCESS;
        }

        $pesan  = "🔔 *Notifikasi Stok – DPM Workshop*\n\n";

        foreach ($barangs as $b) {
            $pesan .= "• {$b->nama_barang} → {$b->stok} {$b->satuan}\n";
        }

        $admin = User::where('role', 'admin')
                     ->where('status', 'aktif')
                     ->first();

        if ($admin) {
            $ok = $wa->sendText($admin->kontak, $pesan);

            if ($ok) {
                Notifikasi::create([
                    'jenis_notifikasi' => 'stok',
                    'judul_notif'      => 'Stok Menipis',
                    'isi_pesan'        => substr($pesan, 0, 150),
                    'tanggal_dibuat'   => now(),
                    'tanggal_dikirim'  => now(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}