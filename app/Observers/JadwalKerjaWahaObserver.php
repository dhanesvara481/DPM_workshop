<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\WahaNotifikasiService;
use Carbon\Carbon;

class JadwalKerjaWahaObserver
{
    private function stripEmoji(string $text): string
    {
        // buang emoji + variation selector + beberapa simbol emoji common
        $text = preg_replace('/[\x{1F1E6}-\x{1F1FF}]/u', '', $text); // flags
        $text = preg_replace('/[\x{1F300}-\x{1FAFF}]/u', '', $text); // emoji range luas
        $text = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $text);   // dingbats/simbol
        $text = preg_replace('/\x{FE0F}/u', '', $text);              // variation selector
        return trim($text);
    }

    // ─── CREATED ─────────────────────────────────────────────────────────────

    public function created(JadwalKerja $jadwal): void
    {
        $notif = app(WahaNotifikasiService::class);

        if (in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            $this->kirimKeSemuaUser($notif, $jadwal, 'buat');
        } else {
            $this->kirimKeStaffTerkait($notif, $jadwal, 'buat');
        }
    }

    // ─── UPDATED ─────────────────────────────────────────────────────────────

    public function updated(JadwalKerja $jadwal): void
    {
        $notif = app(WahaNotifikasiService::class);

        if ($jadwal->isDirty('status') && in_array($jadwal->status, ['Catatan', 'Tutup'])) {
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
            $this->kirimKeStaffTerkait($notif, $jadwal, 'ubah');
        }
    }

    // ─── Helper: kirim ke staff terkait ──────────────────────────────────────

    private function kirimKeStaffTerkait(
        WahaNotifikasiService $notif,
        JadwalKerja $jadwal,
        string $aksi
    ): void {
        $user = $jadwal->user;
        if (!$user || !$user->kontak) return;

        $tanggal    = Carbon::parse($jadwal->tanggal_kerja)->translatedFormat('l, d F Y');
        $jamMulai   = substr($jadwal->jam_mulai   ?? '', 0, 5);
        $jamSelesai = substr($jadwal->jam_selesai ?? '', 0, 5);

        $intro = $aksi === 'buat'
            ? "📅 *JADWAL KERJA BARU*\n\nHalo *{$user->username}*, jadwal baru telah ditambahkan untuk kamu.\n\n"
            : "🔄 *PERUBAHAN JADWAL KERJA*\n\nHalo *{$user->username}*, jadwal kamu telah diperbarui.\n\n";

        $pesan = $intro
            . "──────────────────────\n"
            . "📆 Tanggal  : {$tanggal}\n"
            . "⏰ Shift    : " . ($jadwal->waktu_shift ?? '-') . "\n"
            . "🕐 Jam      : {$jamMulai} – {$jamSelesai}\n"
            . "✅ Status   : {$jadwal->status}\n"
            . ($jadwal->deskripsi ? "📝 Catatan  : {$jadwal->deskripsi}\n" : '')
            . "──────────────────────\n\n"
            . "Cek jadwal kamu di aplikasi DPM Workshop.\n"
            . "_Tim DPM Workshop_";

        $pesanNoEmoji = $this->stripEmoji($pesan);

        $notif->kirimManual(
            $user->kontak,
            $pesanNoEmoji,   // <-- ini yang disimpan ke DB (dan dikirim juga)
            'jadwal',
            $aksi === 'buat' ? 'Jadwal Baru' : 'Perubahan Jadwal'
        );
    }

    // ─── Helper: broadcast ke semua user ─────────────────────────────────────

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
                . "Info lebih lanjut di aplikasi DPM Workshop.\n"
                . "_Tim DPM Workshop_";

        $pesanNoEmoji = $this->stripEmoji($pesan);

        $notif->kirimManual(
            $user->kontak,
            $pesanNoEmoji,
            'jadwal',
            'Info Jadwal ' . $jadwal->status
            );
        }
    }
}