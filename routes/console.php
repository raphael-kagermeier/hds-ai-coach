<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('health:check', function () {
    $this->call(\Spatie\Health\Commands\RunHealthChecksCommand::class);
})->hourly();
