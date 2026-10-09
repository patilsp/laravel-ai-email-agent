<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Periodic Gmail inbox polling every 2 minutes
Schedule::command('email:sync')
    ->everyTwoMinutes()
    ->withoutOverlapping();
