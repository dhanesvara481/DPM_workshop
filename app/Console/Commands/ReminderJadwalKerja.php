<?php

namespace App\Console\Commands;

use App\Models\JadwalKerja;
use App\Services\FonnteService;
use Illuminate\Console\Command;

class ReminderJadwalKerja extends Command
{
    protected $signature   = 'notifikasi:jadwal-kerja';
    protected $description = 'Kirim reminder jadwal kerja besok ke masing-masing staff';

    public function handle(FonnteService $wa): int
    {
        $besok = now()->addDay()->toDateString();

        $jadwals = JadwalKerja::with('user')
            ->whereDate('tanggal_kerja', $besok)
            ->where('status', '!=', 'Tutup')
            ->orderBy('jam_mulai')
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info("Tidak ada jadwal untuk besok ({$besok}).");
            return self::SUCCESS;
        }

        $grouped = $jadwals->groupBy('user_id');

        foreach ($grouped as $userId => $items) {
            $staff = $items->first()->user;

            if (!$staff || empty($staff->kontak) || $staff->status !== 'aktif') {
                $this->warn("Skip {$userId}: kontak kosong / nonaktif.");
                continue;
            }

            $tglFmt = now()->addDay()->translatedFormat('l, d F Y');

            $pesan  = "⏰ *Reminder Jadwal Kerja – DPM Workshop*\n\n";
            $pesan .= "Halo *{$staff->username}*! 👋\n";
            $pesan .= "Jadwal kerja kamu besok:\n";
            $pesan .= "📅 *{$tglFmt}*\n\n";

            foreach ($items as $j) {
                $jamMulai   = substr($j->jam_mulai,   0, 5);
                $jamSelesai = substr($j->jam_selesai, 0, 5);

                $statusEmoji = match (strtolower($j->status)) {
                    'aktif'   => '🟢',
                    'catatan' => '🟡',
                    default   => '⚪',
                };

                $pesan .= "─────────────────\n";
                $pesan .= "{$statusEmoji} *Shift {$j->waktu_shift}*\n";
                $pesan .= "🕐 {$jamMulai} – {$jamSelesai}\n";

                if (!empty($j->deskripsi)) {
                    $pesan .= "📝 {$j->deskripsi}\n";
                }
            }

            $pesan .= "─────────────────\n";
            $pesan .= "\n_Harap hadir tepat waktu. Terima kasih!_ 🙏";

            $ok = $wa->sendText($staff->kontak, $pesan);
            $this->info($ok
                ? "✓ Dikirim ke {$staff->username} ({$staff->kontak})"
                : "✗ Gagal ke {$staff->username} ({$staff->kontak})"
            );
        }

        $this->info('Reminder selesai.');
        return self::SUCCESS;
    }
}