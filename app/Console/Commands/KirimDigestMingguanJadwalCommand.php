<?php

namespace App\Console\Commands;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\GmailNotifikasiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimDigestMingguanJadwalCommand extends Command
{
    protected $signature   = 'jadwal:weekly-digest
                                {--week= : Target minggu format ISO, contoh: 2025-W25. Default: minggu ini.}';

    protected $description = 'Kirim ringkasan jadwal kerja mingguan ke staff (Aktif) dan broadcast Catatan/Tutup ke semua user.';

    public function handle(GmailNotifikasiService $notif): int
    {
        // ── Tentukan rentang minggu ───────────────────────────────────────────
        // ✅ Fix sama
        $senin = $this->option('week')
            ? Carbon::instance(new \DateTime($this->option('week')))->startOfWeek()
            : Carbon::now()->startOfWeek();

        $minggu = $senin->copy()->endOfWeek();

        $this->info("Digest minggu: {$senin->format('d M Y')} – {$minggu->format('d M Y')}");

        // ── Ambil semua jadwal minggu ini ─────────────────────────────────────
        $semuaJadwal = JadwalKerja::with('user')
            ->whereBetween('tanggal_kerja', [$senin->toDateString(), $minggu->toDateString()])
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get();

        if ($semuaJadwal->isEmpty()) {
            $this->warn('Tidak ada jadwal minggu ini. Tidak ada email dikirim.');
            return self::SUCCESS;
        }

        $terkirim = 0;

        // ── 1. Digest personal per staff (status Aktif) ───────────────────────
        $semuaJadwal->where('status', 'Aktif')
            ->groupBy('user_id')
            ->each(function ($jadwals) use ($notif, $senin, $minggu, &$terkirim) {
                $user = $jadwals->first()?->user;
                if (!$user || !$user->email) return;

                $notif->kirimManual(
                    $user->email,
                    "📅 Jadwal Minggu Ini ({$senin->format('d M')} – {$minggu->format('d M Y')})",
                    $this->pesanPersonal($user->username, $jadwals, $senin, $minggu),
                    'jadwal',
                    'Digest Mingguan Jadwal'
                );

                $this->line("  ✓ Personal → {$user->email} ({$user->username})");
                $terkirim++;
            });

        // ── 2. Broadcast Catatan/Tutup ke SEMUA user ──────────────────────────
        $broadcast = $semuaJadwal->whereIn('status', ['Catatan', 'Tutup']);

        if ($broadcast->isNotEmpty()) {
            User::whereNotNull('email')->each(function ($user) use ($notif, $broadcast, $senin, $minggu, &$terkirim) {
                $notif->kirimManual(
                    $user->email,
                    "🔔 Info Penting Jadwal ({$senin->format('d M')} – {$minggu->format('d M Y')})",
                    $this->pesanBroadcast($user->username, $broadcast, $senin, $minggu),
                    'jadwal',
                    'Info Jadwal Catatan/Tutup'
                );

                $this->line("  ✓ Broadcast → {$user->email} ({$user->username})");
                $terkirim++;
            });
        }

        $this->info("Selesai. Total email terkirim: {$terkirim}");
        return self::SUCCESS;
    }

    // ─── Pesan personal (jadwal Aktif) ───────────────────────────────────────

    private function pesanPersonal(string $nama, $jadwals, Carbon $senin, Carbon $minggu): string
    {
        $isi  = "Halo {$nama},\n\n";
        $isi .= "Berikut jadwal kerja kamu minggu ini ";
        $isi .= "({$senin->translatedFormat('d F')} – {$minggu->translatedFormat('d F Y')}):\n\n";
        $isi .= "══════════════════════\n";

        foreach ($jadwals as $j) {
            $tgl  = Carbon::parse($j->tanggal_kerja)->translatedFormat('l, d F Y');
            $dari = substr($j->jam_mulai   ?? '', 0, 5);
            $ke   = substr($j->jam_selesai ?? '', 0, 5);

            $isi .= "📆 {$tgl}\n";
            $isi .= "   Shift  : " . ($j->waktu_shift ?? '-') . "\n";
            $isi .= "   Jam    : {$dari} – {$ke}\n";

            if ($j->deskripsi) {
                $isi .= "   Catatan: {$j->deskripsi}\n";
            }

            $isi .= "\n";
        }

        $isi .= "══════════════════════\n\n";
        $isi .= "Hadir tepat waktu ya! Jika ada perubahan mendadak, admin akan menginformasikan.\n\n";
        $isi .= "Salam,\nTim DPM Workshop";

        return $isi;
    }

    // ─── Pesan broadcast (Catatan/Tutup) ─────────────────────────────────────

    private function pesanBroadcast(string $nama, $jadwals, Carbon $senin, Carbon $minggu): string
    {
        $isi  = "Halo {$nama},\n\n";
        $isi .= "Terdapat informasi penting untuk jadwal minggu ini ";
        $isi .= "({$senin->translatedFormat('d F')} – {$minggu->translatedFormat('d F Y')}):\n\n";
        $isi .= "══════════════════════\n";

        foreach ($jadwals as $j) {
            $tgl   = Carbon::parse($j->tanggal_kerja)->translatedFormat('l, d F Y');
            $label = strtolower($j->status) === 'tutup' ? '🔴 TUTUP' : '🟡 CATATAN';

            $isi .= "{$label}\n";
            $isi .= "📆 {$tgl}\n";

            if ($j->deskripsi) {
                $isi .= "📝 {$j->deskripsi}\n";
            }

            $isi .= "\n";
        }

        $isi .= "══════════════════════\n\n";
        $isi .= "Harap sesuaikan rencana kamu dengan jadwal di atas.\n\n";
        $isi .= "Salam,\nTim DPM Workshop";

        return $isi;
    }
}