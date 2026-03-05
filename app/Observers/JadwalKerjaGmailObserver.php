<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\GmailNotifikasiService;
use App\Services\GmailService;
use Carbon\Carbon;

class JadwalKerjaGmailObserver
{
    // ─── CREATED ─────────────────────────────────────────────────────────────

    public function created(JadwalKerja $jadwal)
    {
        if (in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            $notif = app(GmailNotifikasiService::class);
            $this->kirimKeSemuaUser($notif, $jadwal, 'buat');
        } else {
            $gmail = app(GmailService::class);
            $this->kirimKeStaffTerkait($gmail, $jadwal, 'buat');
        }
    }

    // ─── UPDATED ─────────────────────────────────────────────────────────────

    public function updated(JadwalKerja $jadwal)
    {
        if ($jadwal->isDirty('status') && in_array($jadwal->status, ['Catatan', 'Tutup'])) {
            $notif = app(GmailNotifikasiService::class);
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
            $gmail = app(GmailService::class);
            $this->kirimKeStaffTerkait($gmail, $jadwal, 'ubah');
        }
    }

    // ─── Helper: kirim ke staff terkait (personal) ───────────────────────────

    private function kirimKeStaffTerkait(
        GmailService $gmail,
        JadwalKerja $jadwal,
        string $aksi
    ) {
        $user = $jadwal->user;
        if (!$user || !$user->email) return;

        // Jangan kirim ke user non-aktif
        if ($user->status !== 'aktif') return;

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

        $gmail->sendText($user->email, $subject, $isi);
    }

    // ─── Helper: broadcast ke semua user AKTIF (global) ──────────────────────

    private function kirimKeSemuaUser(
    GmailNotifikasiService $notif,
    JadwalKerja $jadwal,
    string $aksi
) {
    $users   = User::where('status', 'aktif')->whereNotNull('email')->get();
    $tanggal = Carbon::parse($jadwal->tanggal_kerja)->translatedFormat('l, d F Y');
    $isTutup = strtolower($jadwal->status) === 'tutup';

    $icon    = $isTutup ? '🔴' : '🟡';
    $subject = "{$icon} Info Jadwal [{$jadwal->status}] – {$tanggal}";

    // ── Kirim email per user (personal, ada nama) ──
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

    // ── Simpan 1x ke DB notifikasi (generic, tanpa nama user) ──
    if ($users->isNotEmpty()) {
        $pesanLog  = $isTutup
            ? "Workshop tidak beroperasi pada tanggal berikut:\n\n"
            : "Terdapat catatan penting pada jadwal berikut:\n\n";
        $pesanLog .= "──────────────────────\n";
        $pesanLog .= "📆 Tanggal    : {$tanggal}\n";
        $pesanLog .= "🏷️  Status    : {$jadwal->status}\n";
        $pesanLog .= $jadwal->deskripsi ? "📝 Keterangan : {$jadwal->deskripsi}\n" : '';
        $pesanLog .= "──────────────────────\n\n";
        $pesanLog .= "Untuk info lebih lanjut, cek aplikasi DPM Workshop.\n\nSalam,\nTim DPM Workshop";

        \App\Models\Notifikasi::create([
            'jenis_notifikasi' => 'jadwal',
            'judul_notif'      => mb_substr('Info Jadwal ' . $jadwal->status, 0, 100, 'UTF-8'),
            'isi_pesan'        => $pesanLog,
            'tanggal_dibuat'   => now(),
            'tanggal_dikirim'  => now(),
        ]);
    }
}
}