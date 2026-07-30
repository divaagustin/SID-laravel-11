<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Scheduled OpenDK Nightly Data Sync at 01:00 AM
Schedule::job(new \App\Jobs\SyncOpenDkJob())->dailyAt('01:00');
