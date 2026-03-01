<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalKerja;
use App\Services\JadwalNotifikasiService;
use Carbon\Carbon;

class KirimJadwalMingguan extends Command
{
    protected $signature = 'jadwal:kirim-mingguan';
    protected $description = 'Kirim jadwal kerja awal minggu';

    public function handle(JadwalNotifikasiService $notifService)
    {
        $start = Carbon::now('Asia/Makassar')->startOfWeek();
        $end   = Carbon::now('Asia/Makassar')->endOfWeek();

        $jadwals = JadwalKerja::with('user')
            ->whereBetween('tanggal_kerja', [$start, $end])
            ->where('status', '!=', 'Tutup')
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('user_id');

        foreach ($jadwals as $items) {

            $user = $items->first()->user;

            if (!$user || !$user->kontak || $user->status !== 'aktif') {
                continue;
            }

            $pesan  = "📅 *Jadwal Kerja Minggu Ini*\n\n";
            $pesan .= "Halo {$user->username} 👋\n\n";

            foreach ($items as $j) {

                $tgl = Carbon::parse($j->tanggal_kerja)
                    ->translatedFormat('D, d M Y');

                $pesan .= "📌 {$tgl}\n";
                $pesan .= "Shift {$j->waktu_shift}\n";
                $pesan .= "🕐 {$j->jam_mulai}-{$j->jam_selesai}\n";

                if ($j->deskripsi) {
                    $pesan .= "📝 {$j->deskripsi}\n";
                }

                $pesan .= "------------------\n";
            }

            $notifService->kirimManual(
                $user->kontak,
                $pesan,
                'Jadwal Mingguan'
            );
        }

        return Command::SUCCESS;
    }
}