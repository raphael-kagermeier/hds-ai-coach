<?php

namespace App\Providers\Filament;

use App\Filament\Shared\Pages\DataPolicy;
use App\Filament\Shared\Pages\Imprint;
use App\Filament\Shared\Pages\Terms;
use App\Support\SharedPanelConfig;
use Filament\Panel;
use Filament\PanelProvider;

class GuestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return SharedPanelConfig::make($panel, 'guest')
            ->withFooter()
            ->getPanel()
            ->path('')
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/Guest/Resources'), for: 'App\\Filament\\Guest\\Resources')
            ->discoverPages(in: app_path('Filament/Guest/Pages'), for: 'App\\Filament\\Guest\\Pages')
            ->discoverWidgets(in: app_path('Filament/Guest/Widgets'), for: 'App\\Filament\\Guest\\Widgets')
            ->pages([

                Terms::class,
                Imprint::class,
                DataPolicy::class,
            ]);
    }
}
