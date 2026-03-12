<?php

namespace App\Console\Commands;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\WahaNotifikasiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimDigestMingguanJadwalWahaCommand extends Command
{
    protected $signature   = 'jadwal:weekly-digest-wa
                                {--week= : Target minggu format ISO, contoh: 2025-W25. Default: minggu ini.}';

    protected $description = 'Kirim ringkasan jadwal kerja mingguan via WhatsApp (WAHA).';

    public function handle(WahaNotifikasiService $notif): int
    {
        // ✅ FIX — pakai DateTime native PHP lalu convert ke Carbon
        $senin = $this->option('week')
            ? Carbon::instance(new \DateTime($this->option('week')))->startOfWeek()
            : Carbon::now()->startOfWeek();

        $minggu = $senin->copy()->endOfWeek();

        $this->info("Digest minggu: {$senin->format('d M Y')} – {$minggu->format('d M Y')}");

        $semuaJadwal = JadwalKerja::with('user')
            ->whereBetween('tanggal_kerja', [$senin->toDateString(), $minggu->toDateString()])
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get();

        if ($semuaJadwal->isEmpty()) {
            $this->warn('Tidak ada jadwal minggu ini.');
            return self::SUCCESS;
        }

        $terkirim = 0;

        // ── 1. Digest personal per staff (Aktif) ─────────────────────────────
        $semuaJadwal->where('status', 'Aktif')
            ->groupBy('user_id')
            ->each(function ($jadwals) use ($notif, $senin, $minggu, &$terkirim) {
                $user = $jadwals->first()?->user;
                if (!$user || !$user->kontak) return;

                $notif->kirimManual(
                    $user->kontak,
                    $this->pesanPersonal($user->username, $jadwals, $senin, $minggu),
                    'jadwal',
                    'Digest Mingguan Jadwal'
                );

                $this->line("  ✓ Personal → {$user->kontak} ({$user->username})");
                $terkirim++;
            });

        // ── 2. Broadcast Catatan/Tutup ────────────────────────────────────────
        $broadcast = $semuaJadwal->whereIn('status', ['Catatan', 'Tutup']);

        if ($broadcast->isNotEmpty()) {
            User::whereNotNull('kontak')->each(function ($user) use ($notif, $broadcast, $senin, $minggu, &$terkirim) {
                $notif->kirimManual(
                    $user->kontak,
                    $this->pesanBroadcast($user->username, $broadcast, $senin, $minggu),
                    'jadwal',
                    'Info Jadwal Catatan/Tutup'
                );

                $this->line("  ✓ Broadcast → {$user->kontak} ({$user->username})");
                $terkirim++;
            });
        }

        $this->info("Selesai. Total pesan terkirim: {$terkirim}");
        return self::SUCCESS;
    }

    private function pesanPersonal(string $nama, $jadwals, Carbon $senin, Carbon $minggu): string
    {
        $isi = "📅 *JADWAL MINGGU INI*\n"
            . "({$senin->translatedFormat('d F')} – {$minggu->translatedFormat('d F Y')})\n\n"
            . "Halo *{$nama}*,\n\n";

        foreach ($jadwals as $j) {
            $tgl  = Carbon::parse($j->tanggal_kerja)->translatedFormat('l, d F Y');
            $dari = substr($j->jam_mulai   ?? '', 0, 5);
            $ke   = substr($j->jam_selesai ?? '', 0, 5);

            $isi .= "📆 *{$tgl}*\n"
                . "   Shift  : " . ($j->waktu_shift ?? '-') . "\n"
                . "   Jam    : {$dari} – {$ke}\n"
                . ($j->deskripsi ? "   Catatan: {$j->deskripsi}\n" : '')
                . "\n";
        }

        $isi .= "Hadir tepat waktu ya! Semangat! 💪\n_Tim DPM Workshop_";

        return $isi;
    }

    private function pesanBroadcast(string $nama, $jadwals, Carbon $senin, Carbon $minggu): string
    {
        $isi = "🔔 *INFO PENTING JADWAL*\n"
            . "({$senin->translatedFormat('d F')} – {$minggu->translatedFormat('d F Y')})\n\n"
            . "Halo *{$nama}*,\n\n";

        foreach ($jadwals as $j) {
            $tgl   = Carbon::parse($j->tanggal_kerja)->translatedFormat('l, d F Y');
            $label = strtolower($j->status) === 'tutup' ? '🔴 *TUTUP*' : '🟡 *CATATAN*';

            $isi .= "{$label}\n"
                . "📆 {$tgl}\n"
                . ($j->deskripsi ? "📝 {$j->deskripsi}\n" : '')
                . "\n";
        }

        $isi .= "Sesuaikan rencana kamu ya.\n_Tim DPM Workshop_";

        return $isi;
    }
}