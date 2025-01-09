<?php

use Illuminate\Support\Facades\Route;

Route::get('health-check', \Spatie\Health\Http\Controllers\SimpleHealthCheckController::class);
Route::get('robots.txt', App\Http\Controllers\RobotsController::class);

Route::redirect('/app', '/app/generations/generate');
Route::redirect('/', '/app');
