<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('notifications:clear-old')
    ->timezone('Asia/Manila')
    ->everyFiveMinutes();

Schedule::command('app:check-delayed-payments')
    ->timezone('Asia/Manila')
    ->dailyAt('01:00');

Schedule::command('app:violation-task')
    ->timezone('Asia/Manila')
    ->dailyAt('01:00');
