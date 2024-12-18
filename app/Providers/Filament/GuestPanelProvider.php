<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Home;
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
            ->pages([
                Home::class,

                Terms::class,
                Imprint::class,
                DataPolicy::class,
            ]);
    }
}
