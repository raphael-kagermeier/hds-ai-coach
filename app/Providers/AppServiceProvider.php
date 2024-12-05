<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ExceptionsPolicy;
use BezhanSalleh\FilamentExceptions\Models\Exception;
use BezhanSalleh\FilamentShield\FilamentShield;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user) {
            return $user->isSuperAdmin() ? true: null;
        });

        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        Gate::policy(Exception::class, ExceptionsPolicy::class);


    }
}
