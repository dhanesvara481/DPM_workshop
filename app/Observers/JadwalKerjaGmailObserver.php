<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\GmailNotifikasiService;
use Carbon\Carbon;

class JadwalKerjaGmailObserver
{
    // ─── CREATED: dipanggil saat jadwal baru disimpan ─────────────────────────

    public function created(JadwalKerja $jadwal)
    {
        $notif = app(GmailNotifikasiService::class);

        if (in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            $this->kirimKeSemuaUser($notif, $jadwal, 'buat');
        } else {
            $this->kirimKeStaffTerkait($notif, $jadwal, 'buat');
        }
    }

    // ─── UPDATED: dipanggil saat jadwal diubah ────────────────────────────────

    public function updated(JadwalKerja $jadwal)
    {
        $notif = app(GmailNotifikasiService::class);

        // Status berubah ke Catatan/Tutup → broadcast semua user
        if ($jadwal->isDirty('status') && in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            $this->kirimKeSemuaUser($notif, $jadwal, 'ubah');
            return;
        }

        // Data jadwal Aktif berubah → kirim ke staff terkait saja
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
        GmailNotifikasiService $notif,
        JadwalKerja $jadwal,
        string $aksi
    ) {
        $user = $jadwal->user;
        if (!$user || !$user->email) return;

        $tanggal    = Carbon::parse($jadwal->tanggal_kerja)->translatedFormat('l, d F Y');
        $jamMulai   = substr($jadwal->jam_mulai   ?? '', 0, 5);
        $jamSelesai = substr($jadwal->jam_selesai ?? '', 0, 5);

        if ($aksi === 'buat') {
            $subject = '📅 Jadwal Kerja Baru – ' . $tanggal;
            $intro   = "Jadwal kerja baru telah ditambahkan untuk kamu.\n\n";
        } else {
            $subject = '🔄 Perubahan Jadwal Kerja – ' . $tanggal;
            $intro   = "Jadwal kerja kamu telah diperbarui. Berikut detailnya:\n\n";
        }

        $isi  = "Halo {$user->username},\n\n" . $intro;
        $isi .= "──────────────────────\n";
        $isi .= "📆 Tanggal  : {$tanggal}\n";
        $isi .= "⏰ Shift    : " . ($jadwal->waktu_shift ?? '-') . "\n";
        $isi .= "🕐 Jam      : {$jamMulai} – {$jamSelesai}\n";
        $isi .= "✅ Status   : {$jadwal->status}\n";

        if ($jadwal->deskripsi) {
            $isi .= "📝 Catatan  : {$jadwal->deskripsi}\n";
        }

        $isi .= "──────────────────────\n\n";
        $isi .= "Silakan cek jadwal kamu di aplikasi DPM Workshop.\n\n";
        $isi .= "Salam,\nTim DPM Workshop";

        $notif->kirimManual(
            $user->email,
            $subject,
            $isi,
            'jadwal',
            $aksi === 'buat' ? 'Jadwal Baru' : 'Perubahan Jadwal'
        );
    }

    // ─── Helper: broadcast ke semua user ─────────────────────────────────────

    private function kirimKeSemuaUser(
        GmailNotifikasiService $notif,
        JadwalKerja $jadwal,
        string $aksi
    ) {
        $users   = User::whereNotNull('email')->get();
        $tanggal = Carbon::parse($jadwal->tanggal_kerja)->translatedFormat('l, d F Y');
        $isTutup = strtolower($jadwal->status) === 'tutup';

        $icon    = $isTutup ? '🔴' : '🟡';
        $subject = "{$icon} Info Jadwal [{$jadwal->status}] – {$tanggal}";

        foreach ($users as $user) {
            $isi  = "Halo {$user->username},\n\n";
            $isi .= $isTutup
                ? "Workshop *tidak beroperasi* pada tanggal berikut:\n\n"
                : "Terdapat catatan penting pada jadwal berikut:\n\n";

            $isi .= "──────────────────────\n";
            $isi .= "📆 Tanggal    : {$tanggal}\n";
            $isi .= "🏷️  Status    : {$jadwal->status}\n";

            if ($jadwal->deskripsi) {
                $isi .= "📝 Keterangan : {$jadwal->deskripsi}\n";
            }

            $isi .= "──────────────────────\n\n";
            $isi .= "Untuk info lebih lanjut, cek aplikasi DPM Workshop.\n\n";
            $isi .= "Salam,\nTim DPM Workshop";

            $notif->kirimManual(
                $user->email,
                $subject,
                $isi,
                'jadwal',
                'Info Jadwal ' . $jadwal->status
            );
        }
    }
}