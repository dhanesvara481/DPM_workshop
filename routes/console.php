<?php

use Illuminate\Support\Facades\Schedule;


    Schedule::command('jadwal:weekly-digest')
            ->weeklyOn(1, '07:00')
            ->timezone('Asia/Makassar');

    Schedule::command('jadwal:reminder-h1')
            ->dailyAt('01:05')
            ->timezone('Asia/Makassar');
