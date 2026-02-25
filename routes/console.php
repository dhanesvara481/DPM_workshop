<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notifikasi:stok-menipis')
    ->dailyAt('08:00')
    ->withoutOverlapping();

Schedule::command('notifikasi:jadwal-kerja')
    ->dailyAt('20:00')
    ->withoutOverlapping();