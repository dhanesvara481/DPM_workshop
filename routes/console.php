<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('jadwal:weekly-digest')
        ->weeklyOn(1, '08:00')
        ->timezone('Asia/Makassar');

Schedule::command('jadwal:reminder-h1')     
        ->dailyAt('12:00')
        ->timezone('Asia/Makassar');
