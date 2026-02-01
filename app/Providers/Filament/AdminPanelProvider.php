<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])

            ->discoverResources(
                in: app_path('Modules/Project/Filament/Resources'),
                for: 'App\\Modules\\Project\\Filament\\Resources'
            )
            ->discoverResources(
                in: app_path('Modules/Task/Filament/Resources'),
                for: 'App\\Modules\\Task\\Filament\\Resources'
            )
            ->discoverResources(
                in: app_path('Modules/User/Filament/Resources'),
                for: 'App\\Modules\\User\\Filament\\Resources'
            )
            ->discoverResources(
                in: app_path('Modules/Permissions/Filament/Resources'),
                for: 'App\\Modules\\Permissions\\Filament\\Resources'
            )
            ->discoverResources(
                in: app_path('Modules/Telemetry/Filament/Resources'),
                for: 'App\\Modules\\Telemetry\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Modules'),
                for: 'App\\Modules'
            )
            ->discoverWidgets(
                in: app_path('Modules'),
                for: 'App\\Modules'
            )
            ->discoverLivewireComponents(
                in: app_path('Livewire'),
                for: 'App\\Livewire'
            )
            ->sidebarFullyCollapsibleOnDesktop()
            ->authGuard('web')
            ->plugins([
                BreezyCore::make()
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload->disableLabel())
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)],
                    )
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(
                        permissions: ['viewAny', 'create', 'update', 'delete'],
                    )
                    ->enableBrowserSessions()
                    ->myProfile(
                        hasAvatars: true,
                        navigationGroup: 'Settings',
                        userMenuLabel: 'My Profile',
                    ),
            ])

            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])

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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
