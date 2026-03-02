<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\GmailNotifikasiService;

class JadwalKerjaGmailObserver
{
    public function updated(JadwalKerja $jadwal): void
    {
        $notif = app(GmailNotifikasiService::class);

        // 1) Status Catatan/Tutup -> kirim ke semua user yang punya email
        if ($jadwal->isDirty('status') && in_array($jadwal->status, ['Catatan', 'Tutup'])) {

            $users = User::whereNotNull('email')->get();

            foreach ($users as $user) {

                $pesan  = "📢 Informasi Jadwal\n\n";
                $pesan .= "Tanggal: {$jadwal->tanggal_kerja}\n";
                $pesan .= "Shift: {$jadwal->waktu_shift}\n";
                $pesan .= "Status: {$jadwal->status}\n";

                if ($jadwal->deskripsi) {
                    $pesan .= "Catatan: {$jadwal->deskripsi}\n";
                }

                $notif->kirimManual(
                    $user->email,
                    'Info Jadwal',
                    $pesan,
                    'jadwal',
                    'Info Jadwal'
                );
            }
        }

        // 2) Perubahan jadwal -> kirim ke user terkait
        if (
            $jadwal->isDirty('tanggal_kerja') ||
            $jadwal->isDirty('waktu_shift') ||
            $jadwal->isDirty('jam_mulai') ||
            $jadwal->isDirty('jam_selesai')
        ) {
            $user = $jadwal->user;

            if ($user && $user->email) {

                $pesan  = "🔄 Perubahan Jadwal\n\n";
                $pesan .= "Halo {$user->username}\n\n";
                $pesan .= "Tanggal: {$jadwal->tanggal_kerja}\n";
                $pesan .= "Shift: {$jadwal->waktu_shift}\n";
                $pesan .= "Jam: {$jadwal->jam_mulai} - {$jadwal->jam_selesai}\n";

                if ($jadwal->deskripsi) {
                    $pesan .= "Catatan: {$jadwal->deskripsi}\n";
                }

                $pesan .= "\nSilakan cek kembali jadwal kamu.";

                $notif->kirimManual(
                    $user->email,
                    'Perubahan Jadwal',
                    $pesan,
                    'jadwal',
                    'Perubahan Jadwal'
                );
            }
        }
    }
}