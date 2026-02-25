<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Console\Command;

class NotifikasiStokMenipis extends Command
{
    protected $signature   = 'notifikasi:stok-menipis';
    protected $description = 'Kirim notifikasi WA stok menipis / habis ke admin & staff';

    public function handle(FonnteService $wa): int
    {
        $threshold = (int) env('STOK_MIN_THRESHOLD', 25);

        $barangs = Barang::query()
            ->where('stok', '<', $threshold)
            ->orderBy('stok', 'asc')
            ->get();

        if ($barangs->isEmpty()) {
            $this->info('Semua stok aman.');
            return self::SUCCESS;
        }

        $habis   = $barangs->where('stok', '<=', 0);
        $menipis = $barangs->where('stok', '>', 0);
        $tgl     = now()->format('d/m/Y H:i');

        $pesan  = "🔔 *Notifikasi Stok – DPM Workshop*\n";
        $pesan .= "📅 {$tgl}\n\n";

        if ($habis->isNotEmpty()) {
            $pesan .= "🔴 *BARANG HABIS ({$habis->count()} item):*\n";
            foreach ($habis as $b) {
                $pesan .= "  • [{$b->kode_barang}] {$b->nama_barang} → *0 {$b->satuan}*\n";
            }
            $pesan .= "\n";
        }

        if ($menipis->isNotEmpty()) {
            $pesan .= "🟡 *STOK MENIPIS ({$menipis->count()} item):*\n";
            foreach ($menipis as $b) {
                $pesan .= "  • [{$b->kode_barang}] {$b->nama_barang} → *{$b->stok} {$b->satuan}*\n";
            }
            $pesan .= "\n";
        }

        $pesan .= "_Segera lakukan restok. Terima kasih._";

        // Kirim ke admin
        $adminNomor = env('ADMIN_WA_NUMBER');
        if ($adminNomor) {
            $wa->sendText($adminNomor, $pesan);
            $this->info("✓ Dikirim ke admin: {$adminNomor}");
        }

        // Kirim ke semua staff aktif
        $staffs = User::where('role', 'staff')
            ->where('status', 'aktif')
            ->whereNotNull('kontak')
            ->where('kontak', '!=', '')
            ->get();

        foreach ($staffs as $staff) {
            $pesanStaff  = "🔔 *Info Stok – DPM Workshop*\n";
            $pesanStaff .= "Halo *{$staff->username}*, berikut update stok:\n\n";
            $pesanStaff .= $pesan;

            $ok = $wa->sendText($staff->kontak, $pesanStaff);
            $this->info($ok
                ? "✓ Dikirim ke {$staff->username} ({$staff->kontak})"
                : "✗ Gagal ke {$staff->username} ({$staff->kontak})"
            );
        }

        $this->info('Selesai. Total: ' . $barangs->count() . ' barang bermasalah.');
        return self::SUCCESS;
    }
}