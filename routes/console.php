<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('jadwal:weekly-digest')
        ->weeklyOn(1, '07:00')
        ->timezone('Asia/Makassar');

// Reminder H-1: setiap hari jam 18:00
Schedule::command('jadwal:reminder-h1')
        ->dailyAt('23:10')
        ->timezone('Asia/Makassar');