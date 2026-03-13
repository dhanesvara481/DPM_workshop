<?php

use Illuminate\Support\Facades\Schedule;

// ── WhatsApp ──
Schedule::command('jadwal:weekly-digest-wa')
        ->weeklyOn(1, '14:26')
        ->timezone('Asia/Makassar');

Schedule::command('jadwal:reminder-h1-wa')
        ->dailyAt('08:00')
        ->timezone('Asia/Makassar');
