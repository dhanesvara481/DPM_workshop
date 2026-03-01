<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Kirim jadwal mingguan setiap Senin jam 08:00
        $schedule->command('jadwal:kirim-mingguan')
                 ->weeklyOn(1, '08:00');
    
        // Kirim reminder H-1 setiap hari jam 18:00 ✅ diperbaiki
        $schedule->command('jadwal:reminder-h1')
                 ->dailyAt('18:00');
    
        // Notifikasi stok menipis setiap hari jam 08:00
        // (sudah ada di console.php, pilih salah satu tempatnya)
        $schedule->command('notifikasi:stok-menipis')
                 ->dailyAt('08:00')
                 ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}