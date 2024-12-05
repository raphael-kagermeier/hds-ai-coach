<?php

namespace App\Support;

use App\Models\User;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Kainiklas\FilamentScout\FilamentScoutPlugin;

class SharedPanelConfig
{

    public function __construct(protected Panel $panel)
    {
    }

    public static function make(Panel $panel, string $id = 'admin'): static
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return tap(
            new self($panel),
            fn(self $obj) => $obj->defaultConfig($id)
        );
    }

    protected function defaultConfig(string $id = 'admin'): static
    {
        $this->panel
            ->id($id)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->font('Inter')
            ->brandLogo(fn() => view('components.brand.logo'))
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Blue,
                'info' => Color::Blue,
                'primary' => Color::Amber,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->globalSearchFieldSuffix(fn(): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
            ->globalSearchFieldSuffix(fn(): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
            ->login()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->plugins([
                BreezyCore::make()->myProfile(),
                FilamentShieldPlugin::make(),
                FilamentScoutPlugin::make(),
                GlobalSearchModalPlugin::make()
                    ->expandedUrlTarget(enabled: true)
                    ->localStorageMaxItemsAllowed(25)
                    ->searchItemTree(false)
            ]);

        $this->enableDeveloperLoginButton();

        return $this;
    }

    public function withAuthentication(): static
    {

        $this->panel->login()
            ->registration()
            ->passwordReset();

        return $this;
    }

    public function superAdminPanel()
    {
        $this->panel
            ->plugin(FilamentExceptionsPlugin::make());

        return $this;
    }

    public function enableDeveloperLoginButton(): static
    {
        $this->panel->plugin(
            FilamentDeveloperLoginsPlugin::make()
                ->users(fn() => User::pluck('email', 'name')->toArray())
                ->enabled(app()->environment('local'))
        );

        return $this;

    }

    /**
     * @return Panel
     */
    public function getPanel(): Panel
    {
        return $this->panel;
    }


}
