<?php

namespace App\Providers\Filament;

use App\Support\SharedPanelConfig;
use Filament\Http\Middleware\Authenticate;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return SharedPanelConfig::make($panel)
            ->superAdminPanel()
            ->getPanel()
            ->default()
            ->path('admin')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Admin\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Admin\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Admin\\Filament\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
