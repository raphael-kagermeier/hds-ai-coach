<?php

namespace App\Providers\Filament;

use App\Support\SharedPanelConfig;
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
            ->pages([
                Pages\Dashboard::class,
            ]);
    }
}
