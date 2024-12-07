<?php

namespace App\Support;

use App\Filament\Shared\Pages\Register;
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
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Kainiklas\FilamentScout\FilamentScoutPlugin;
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;

class SharedPanelConfig
{
    public function __construct(protected Panel $panel) {}

    public static function make(Panel $panel, string $id = 'admin'): static
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return tap(
            new self($panel),
            fn (self $obj) => $obj->defaultConfig($id)
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
            ->brandLogo(fn () => view('components.brand.logo'))
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Blue,
                'info' => Color::Blue,
                'primary' => Color::Amber,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->plugins([
                BreezyCore::make()->myProfile(),
                FilamentShieldPlugin::make(),
                FilamentScoutPlugin::make(),
                GlobalSearchModalPlugin::make()
                    ->expandedUrlTarget(enabled: true)
                    ->localStorageMaxItemsAllowed(25)
                    ->searchItemTree(false),
            ]);

        return $this;
    }

    public function withAuthentication(): static
    {

        $this->withDeveloperLoginButton();
        $this->panel->login()
            ->registration(Register::class)
            ->passwordReset();

        return $this;
    }

    public function withFooter(): static
    {
        $this->panel->renderHook(
            PanelsRenderHook::FOOTER, fn () => view('filament.footer')
        );

        return $this;
    }

    public function superAdminPanel()
    {
        $this->panel
            ->plugin(FilamentUsersPlugin::make())
            ->plugin(FilamentExceptionsPlugin::make());

        return $this;
    }

    public function withDeveloperLoginButton(): static
    {
        $this->panel->plugin(
            FilamentDeveloperLoginsPlugin::make()
                ->users(fn () => User::pluck('email', 'name')->toArray())
                ->enabled(app()->environment('local'))
        );

        return $this;

    }

    public function getPanel(): Panel
    {
        return $this->panel;
    }
}
