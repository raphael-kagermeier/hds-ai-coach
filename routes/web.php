<?php

use Illuminate\Support\Facades\Route;

Route::get('health-check', \Spatie\Health\Http\Controllers\SimpleHealthCheckController::class);
