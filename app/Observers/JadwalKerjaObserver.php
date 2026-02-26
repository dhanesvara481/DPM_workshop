<?php

namespace App\Observers;

use App\Models\JadwalKerja;
use App\Models\User;
use App\Services\JadwalNotifikasiService;

class JadwalKerjaObserver
{
    public function updated(JadwalKerja $jadwal)
    {
        $notif = app(JadwalNotifikasiService::class);

        /*
        |--------------------------------------------------------------------------
        | Status Catatan atau Tutup → Kirim ke semua user
        |--------------------------------------------------------------------------
        */
        if (
            $jadwal->isDirty('status') &&
            in_array($jadwal->status, ['Catatan', 'Tutup'])
        ) {

            $users = User::whereNotNull('kontak')->get();

            foreach ($users as $user) {

                $pesan  = "📢 *Informasi Jadwal*\n\n";
                $pesan .= "Tanggal: {$jadwal->tanggal_kerja}\n";
                $pesan .= "Shift: {$jadwal->waktu_shift}\n";
                $pesan .= "Status: {$jadwal->status}\n";

                if ($jadwal->deskripsi) {
                    $pesan .= "📝 {$jadwal->deskripsi}\n";
                }

                $notif->kirimManual(
                    $user->kontak,
                    $pesan,
                    'Info Jadwal'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Perubahan jadwal → Kirim ke user terkait
        |--------------------------------------------------------------------------
        */
        if (
            $jadwal->isDirty('tanggal_kerja') ||
            $jadwal->isDirty('waktu_shift') ||
            $jadwal->isDirty('jam_mulai') ||
            $jadwal->isDirty('jam_selesai')
        ) {

            $user = $jadwal->user;

            if ($user && $user->kontak) {

                $pesan  = "🔄 *Perubahan Jadwal*\n\n";
                $pesan .= "Halo {$user->username}\n\n";
                $pesan .= "Tanggal: {$jadwal->tanggal_kerja}\n";
                $pesan .= "Shift: {$jadwal->waktu_shift}\n";
                $pesan .= "Jam: {$jadwal->jam_mulai} - {$jadwal->jam_selesai}\n";

                if ($jadwal->deskripsi) {
                    $pesan .= "📝 {$jadwal->deskripsi}\n";
                }

                $pesan .= "\nSilakan cek kembali jadwal kamu.";

                $notif->kirimManual(
                    $user->kontak,
                    $pesan,
                    'Perubahan Jadwal'
                );
            }
        }
    }
}