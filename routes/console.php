<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\CleanupLivewireTempFiles;

Schedule::command('queue:work --tries=3 --timeout=8600 --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('cache:clear')
    ->weekly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::job(new CleanupLivewireTempFiles)->hourly();
