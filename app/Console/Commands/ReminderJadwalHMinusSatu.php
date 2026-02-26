<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalKerja;
use App\Services\JadwalNotifikasiService;
use Carbon\Carbon;

class ReminderJadwalHMinusSatu extends Command
{
    protected $signature = 'jadwal:reminder-h1';
    protected $description = 'Reminder jadwal kerja H-1';

    public function handle(JadwalNotifikasiService $notifService)
    {
        $besok = Carbon::tomorrow();

        $jadwals = JadwalKerja::with('user')
            ->whereDate('tanggal_kerja', $besok)
            ->where('status', '!=', 'Tutup')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('user_id');

        foreach ($jadwals as $items) {

            $user = $items->first()->user;

            if (!$user || !$user->kontak || $user->status !== 'aktif') {
                continue;
            }

            $tgl = $besok->translatedFormat('l, d F Y');

            $pesan  = "⏰ *Reminder Jadwal Kerja*\n\n";
            $pesan .= "Halo {$user->username} 👋\n";
            $pesan .= "📅 {$tgl}\n\n";

            foreach ($items as $j) {

                $pesan .= "Shift {$j->waktu_shift}\n";
                $pesan .= "🕐 {$j->jam_mulai} - {$j->jam_selesai}\n";

                if ($j->deskripsi) {
                    $pesan .= "📝 {$j->deskripsi}\n";
                }

                $pesan .= "------------------\n";
            }

            $pesan .= "\nHarap hadir tepat waktu 🙏";

            $notifService->kirimManual(
                $user->kontak,
                $pesan,
                'Reminder H-1'
            );
        }

        return Command::SUCCESS;
    }
}