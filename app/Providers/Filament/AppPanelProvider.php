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
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
