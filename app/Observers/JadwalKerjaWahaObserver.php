<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\WahaNotifikasiService;
use App\Services\WahaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class JadwalKerjaWahaObserver
{
    // ─── CREATED ─────────────────────────────────────────────────────────────

    public function created(JadwalKerja $jadwal): void
    {
        if (in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            // Global → simpan ke notifikasi
            $notif = app(WahaNotifikasiService::class);
            $this->kirimKeSemuaUser($notif, $jadwal, 'buat');
        } else {
            // Personal (Aktif) → buffer ke cache, kirim setelah request selesai
            $this->bufferJadwal($jadwal, 'buat');
        }
    }

    // ─── UPDATED ─────────────────────────────────────────────────────────────

    public function updated(JadwalKerja $jadwal): void
    {
        if ($jadwal->isDirty('status') && in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            // Global → simpan ke notifikasi
            $notif = app(WahaNotifikasiService::class);
            $this->kirimKeSemuaUser($notif, $jadwal, 'ubah');
            return;
        }

        if (
            $jadwal->isDirty('tanggal_kerja') ||
            $jadwal->isDirty('waktu_shift')   ||
            $jadwal->isDirty('jam_mulai')     ||
            $jadwal->isDirty('jam_selesai')   ||
            $jadwal->isDirty('status')
        ) {
            // Personal (Aktif) → buffer ke cache, kirim setelah request selesai
            $this->bufferJadwal($jadwal, 'ubah');
        }
    }

    // ─── Buffer jadwal ke cache & daftarkan terminating callback (1x per request) ─

    private function bufferJadwal(JadwalKerja $jadwal, string $aksi): void
    {
        $user = $jadwal->user;
        if (!$user || !$user->kontak) return;

        $cacheKey = "wa_jadwal_buffer_{$user->id}_{$aksi}";

        // Ambil buffer yang sudah ada, tambahkan jadwal baru
        $buffer = Cache::get($cacheKey, []);
        $buffer[] = [
            'tanggal'    => $jadwal->tanggal_kerja,
            'shift'      => $jadwal->waktu_shift ?? '-',
            'jam_mulai'  => substr($jadwal->jam_mulai   ?? '', 0, 5),
            'jam_selesai'=> substr($jadwal->jam_selesai ?? '', 0, 5),
            'status'     => $jadwal->status,
            'deskripsi'  => $jadwal->deskripsi,
        ];

        // Simpan buffer selama 5 menit (lebih dari cukup untuk 1 request)
        Cache::put($cacheKey, $buffer, now()->addMinutes(5));

        // Daftarkan terminating callback — hanya 1x per user per aksi per request
        $flagKey = "wa_jadwal_registered_{$user->id}_{$aksi}";
        if (!Cache::has($flagKey)) {
            Cache::put($flagKey, true, now()->addMinutes(5));

            // Capture data yang dibutuhkan untuk closure
            $userId   = $user->id;
            $kontak   = $user->kontak;
            $username = $user->username;

            app()->terminating(function () use ($userId, $kontak, $username, $aksi, $cacheKey, $flagKey) {
                $buffer = Cache::pull($cacheKey); // ambil & hapus
                Cache::forget($flagKey);

                if (empty($buffer)) return;

                $waha  = app(WahaService::class);
                $pesan = $this->buildPesanBatch($username, $buffer, $aksi);
                $waha->sendText($kontak, $pesan);
            });
        }
    }

    // ─── Build pesan WA gabungan (batch) ─────────────────────────────────────

    private function buildPesanBatch(string $username, array $buffer, string $aksi): string
    {
        // Urutkan berdasarkan tanggal
        usort($buffer, fn($a, $b) => $a['tanggal'] <=> $b['tanggal']);

        $jumlah = count($buffer);
        $header = $aksi === 'buat'
            ? "📅 *JADWAL KERJA BARU*\n\nHalo *{$username}*, {$jumlah} jadwal baru telah ditambahkan untuk kamu.\n\n"
            : "🔄 *PERUBAHAN JADWAL KERJA*\n\nHalo *{$username}*, {$jumlah} jadwal kamu telah diperbarui.\n\n";

        $isi = $header;

        foreach ($buffer as $j) {
            $tanggal = Carbon::parse($j['tanggal'])->translatedFormat('l, d F Y');

            $isi .= "──────────────────────\n"
                . "📆 Tanggal  : {$tanggal}\n"
                . "⏰ Shift    : {$j['shift']}\n"
                . "🕐 Jam      : {$j['jam_mulai']} – {$j['jam_selesai']}\n"
                . "✅ Status   : {$j['status']}\n"
                . ($j['deskripsi'] ? "📝 Catatan  : {$j['deskripsi']}\n" : '');
        }

        $isi .= "──────────────────────\n\n"
            . "Cek jadwal kamu di aplikasi DPM Workshop.\n"
            . "_Tim DPM Workshop_";

        return $isi;
    }

    // ─── Helper: broadcast ke semua user (global — simpan ke notifikasi) ──────

    private function kirimKeSemuaUser(
        WahaNotifikasiService $notif,
        JadwalKerja $jadwal,
        string $aksi
    ): void {
        $users   = User::whereNotNull('kontak')->get();
        $tanggal = Carbon::parse($jadwal->tanggal_kerja)->translatedFormat('l, d F Y');
        $isTutup = strtolower($jadwal->status) === 'tutup';

        $icon  = $isTutup ? '🔴' : '🟡';
        $judul = $isTutup
            ? "{$icon} *WORKSHOP TUTUP*"
            : "{$icon} *INFO JADWAL - CATATAN*";

        foreach ($users as $user) {
            $intro = $isTutup
                ? "Workshop *tidak beroperasi* pada tanggal berikut:\n\n"
                : "Terdapat catatan penting pada jadwal berikut:\n\n";

            $pesan = "Halo *{$user->username}*,\n\n"
                . $judul . "\n\n"
                . $intro
                . "──────────────────────\n"
                . "📆 Tanggal    : {$tanggal}\n"
                . "🏷️  Status    : {$jadwal->status}\n"
                . ($jadwal->deskripsi ? "📝 Keterangan : {$jadwal->deskripsi}\n" : '')
                . "──────────────────────\n\n"
                . "Info lebih lanjut di sistem DPM Workshop.\n"
                . "_Tim DPM Workshop_";

            $notif->kirimManual(
                $user->kontak,
                $pesan,
                'jadwal',
                'Info Jadwal ' . $jadwal->status
            );
        }
    }
}