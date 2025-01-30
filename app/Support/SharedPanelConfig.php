<?php

namespace App\Support;

use App\Filament\Admin\Pages\HealthCheckResults;
use App\Filament\Shared\Pages\Register;
use App\Models\User;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class SharedPanelConfig
{
    public function __construct(protected Panel $panel) {}

    public static function make(Panel $panel, string $id = 'admin'): self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return tap(
            new self($panel),
            fn (self $obj) => $obj->defaultConfig($id)
        );
    }

    protected function defaultConfig(string $id = 'admin'): self
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
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->homeUrl(fn () => Filament::getPanel(auth()->user() ? 'app' : 'guest')->getUrl())
            ->userMenuItems([
                'admin' => MenuItem::make()
                    ->label('Admin Home')
                    ->icon('heroicon-o-lock-closed')
                    /** @disregard */
                    ->visible(fn () => auth()->user()->isSuperAdmin())
                    ->url(fn () => Filament::getPanel('admin')->getUrl()),
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        hasAvatars: true,
                        shouldRegisterNavigation: false,
                    )
                    ->enableTwoFactorAuthentication(
                        force: false,
                    )
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload
                        ->extraAttributes(['class' => 'h-full flex items-center px-12'])
                        ->disableLabel()
                        ->disk('avatars')
                        ->avatar()
                        ->imageEditor()
                        ->circleCropper()),
                FilamentScoutPlugin::make(),
                GlobalSearchModalPlugin::make()
                    ->expandedUrlTarget(enabled: true)
                    ->localStorageMaxItemsAllowed(25)
                    ->searchItemTree(false),
            ]);

        $this->withDeveloperLoginButton();

        return $this;
    }

    public function withAuthentication(): self
    {

        $this->withDeveloperLoginButton();
        $this->panel->login()
            ->registration(Register::class)
            ->passwordReset();

        return $this;
    }

    public function withFooter(): self
    {
        $this->panel->renderHook(
            PanelsRenderHook::FOOTER,
            fn () => view('filament.footer')
        );

        return $this;
    }

    public function superAdminPanel()
    {
        $this->panel
            ->plugin(FilamentShieldPlugin::make())
            ->plugin(
                FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class)
            )
            ->plugin(FilamentExceptionsPlugin::make());

        return $this;
    }

    public function withDeveloperLoginButton(): self
    {
        $this->panel->plugin(
            FilamentDeveloperLoginsPlugin::make()
                ->users(fn () => User::pluck('email', 'name')->toArray())
                ->enabled(config('app.debug'))
        );

        return $this;
    }

    public function getPanel(): Panel
    {
        return $this->panel;
    }
}
