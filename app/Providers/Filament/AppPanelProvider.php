<?php

namespace App\Providers\Filament;

use App\Support\SharedPanelConfig;
use Filament\Http\Middleware\Authenticate;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return SharedPanelConfig::make($panel, 'app')
            ->withAuthentication()
            ->withDeveloperLoginButton()
            ->getPanel()
            ->path('app')
            ->default()
            ->pages([
                Pages\Dashboard::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
