<?php

use App\Console\Commands\ManageSystemVersion;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    echo Inspiring::quote();
})->purpose('Display an inspiring quote');

// Register system version management command
Artisan::command('system:version', function () {
    $command = app(ManageSystemVersion::class);
    $command->handle();
})->purpose('Manage system versions');
