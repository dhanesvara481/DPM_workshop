<?php

namespace App\Console\Commands;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\GmailNotifikasiService;
use App\Services\GmailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimReminderH1JadwalCommand extends Command
{
    protected $signature   = 'jadwal:reminder-h1
                                {--date= : Tanggal jadwal yang diperiksa (Y-m-d). Default: besok.}';

    protected $description = 'Kirim reminder H-1 ke staff yang punya jadwal besok.';

    public function handle(GmailNotifikasiService $notif, GmailService $gmail): int
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
            $this->warn('Tidak ada jadwal besok. Tidak ada reminder dikirim.');
            return self::SUCCESS;
        }

        $terkirim = 0;
        $tanggal  = $besok->translatedFormat('l, d F Y');

        foreach ($jadwals as $j) {
            $isTutup = strtolower($j->status) === 'tutup';

            if ($isTutup) {
                // Tutup → broadcast ke semua user → SIMPAN ke notifikasi (global)
                User::whereNotNull('email')->each(function ($user) use ($notif, $j, $tanggal, &$terkirim) {
                    $isi  = "Halo {$user->username},\n\n";
                    $isi .= "Pengingat: workshop *tidak beroperasi* besok.\n\n";
                    $isi .= "──────────────────────\n";
                    $isi .= "📆 Tanggal    : {$tanggal}\n";
                    $isi .= "🔴 Status     : TUTUP\n";

                    if ($j->deskripsi) {
                        $isi .= "📝 Keterangan : {$j->deskripsi}\n";
                    }

                    $isi .= "──────────────────────\n\n";
                    $isi .= "Salam,\nTim DPM Workshop";

                    $notif->kirimManual(
                        $user->email,
                        "🔴 Reminder: Workshop Tutup Besok – {$tanggal}",
                        $isi,
                        'jadwal',
                        'Reminder H-1 Tutup'
                    );

                    $this->line("  ✓ Broadcast Tutup → {$user->email}");
                    $terkirim++;
                });

                continue;
            }

            // Aktif / Catatan → kirim ke staff terkait saja
            // TIDAK simpan ke notifikasi (personal)
            $user = $j->user;
            if (!$user || !$user->email) continue;

            $jamMulai   = substr($j->jam_mulai   ?? '', 0, 5);
            $jamSelesai = substr($j->jam_selesai ?? '', 0, 5);
            $isCatatan  = strtolower($j->status) === 'catatan';

            $isi  = "Halo {$user->username},\n\n";
            $isi .= "Pengingat: kamu memiliki jadwal kerja *besok*.\n\n";
            $isi .= "──────────────────────\n";
            $isi .= "📆 Tanggal  : {$tanggal}\n";
            $isi .= "⏰ Shift    : " . ($j->waktu_shift ?? '-') . "\n";
            $isi .= "🕐 Jam      : {$jamMulai} – {$jamSelesai}\n";
            $isi .= "✅ Status   : {$j->status}\n";

            if ($j->deskripsi) {
                $isi .= "📝 Catatan  : {$j->deskripsi}\n";
            }

            $isi .= "──────────────────────\n\n";
            $isi .= "Hadir tepat waktu ya! Semangat kerja!\n\n";
            $isi .= "Salam,\nTim DPM Workshop";

            $icon    = $isCatatan ? '🟡' : '📅';
            $subject = "{$icon} Reminder Jadwal Besok – {$tanggal}";

            // Langsung kirim via GmailService — TIDAK lewat GmailNotifikasiService
            // agar tidak tersimpan ke tabel notifikasi
            $gmail->sendText($user->email, $subject, $isi);

            $this->line("  ✓ Reminder H-1 → {$user->email} ({$user->username})");
            $terkirim++;
        }

        $this->info("Selesai. Total email terkirim: {$terkirim}");
        return self::SUCCESS;
    }
}