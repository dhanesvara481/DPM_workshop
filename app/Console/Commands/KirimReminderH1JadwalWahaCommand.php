<?php

namespace App\Console\Commands;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\WahaNotifikasiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimReminderH1JadwalWahaCommand extends Command
{
    protected $signature   = 'jadwal:reminder-h1-wa
                                {--date= : Tanggal jadwal yang diperiksa (Y-m-d). Default: besok.}';

    protected $description = 'Kirim reminder H-1 jadwal kerja via WhatsApp (WAHA).';

    public function handle(WahaNotifikasiService $notif): int
    {
        $besok = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::tomorrow();

        $this->info("Cek jadwal untuk: {$besok->format('d M Y')}");

        $jadwals = JadwalKerja::with('user')
            ->whereDate('tanggal_kerja', $besok->toDateString())
            ->orderBy('jam_mulai')
            ->get();

        if ($jadwals->isEmpty()) {
            $this->warn('Tidak ada jadwal besok.');
            return self::SUCCESS;
        }

        $terkirim = 0;
        $tanggal  = $besok->translatedFormat('l, d F Y');

        foreach ($jadwals as $j) {
            $isTutup = strtolower($j->status) === 'tutup';

            if ($isTutup) {
                // Broadcast ke semua user
                User::whereNotNull('kontak')->each(function ($user) use ($notif, $j, $tanggal, &$terkirim) {
                    $pesan = "🔴 *WORKSHOP TUTUP BESOK*\n\n"
                        . "Halo *{$user->username}*,\n\n"
                        . "Pengingat: workshop *tidak beroperasi* besok.\n\n"
                        . "──────────────────────\n"
                        . "📆 Tanggal    : {$tanggal}\n"
                        . "🔴 Status     : TUTUP\n"
                        . ($j->deskripsi ? "📝 Keterangan : {$j->deskripsi}\n" : '')
                        . "──────────────────────\n\n"
                        . "_Tim DPM Workshop_";

                    $notif->kirimManual($user->kontak, $pesan, 'jadwal', 'Reminder H-1 Tutup');

                    $this->line("  ✓ Broadcast Tutup → {$user->kontak}");
                    $terkirim++;
                });

                continue;
            }

            // Aktif / Catatan → kirim ke staff terkait
            $user = $j->user;
            if (!$user || !$user->kontak) continue;

            $jamMulai   = substr($j->jam_mulai   ?? '', 0, 5);
            $jamSelesai = substr($j->jam_selesai ?? '', 0, 5);
            $isCatatan  = strtolower($j->status) === 'catatan';
            $icon       = $isCatatan ? '🟡' : '📅';

            $pesan = "{$icon} *REMINDER JADWAL BESOK*\n\n"
                . "Halo *{$user->username}*,\n\n"
                . "Pengingat: kamu memiliki jadwal kerja *besok*.\n\n"
                . "──────────────────────\n"
                . "📆 Tanggal  : {$tanggal}\n"
                . "⏰ Shift    : " . ($j->waktu_shift ?? '-') . "\n"
                . "🕐 Jam      : {$jamMulai} – {$jamSelesai}\n"
                . "✅ Status   : {$j->status}\n"
                . ($j->deskripsi ? "📝 Catatan  : {$j->deskripsi}\n" : '')
                . "──────────────────────\n\n"
                . "Hadir tepat waktu ya! Semangat! 💪\n"
                . "_Tim DPM Workshop_";

            $notif->kirimManual($user->kontak, $pesan, 'jadwal', 'Reminder H-1 Jadwal');

            $this->line("  ✓ Reminder H-1 → {$user->kontak} ({$user->username})");
            $terkirim++;
        }

        $this->info("Selesai. Total pesan terkirim: {$terkirim}");
        return self::SUCCESS;
    }
}