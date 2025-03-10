<?php

namespace App\Providers\Filament;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\VerifyEmail;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;

class ManagePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('manage')
            ->path('manage')
            ->login(Login::class)
            ->emailVerification(VerifyEmail::class)
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => [
                    50 => '#fff1f1',
                    100 => '#ffe1e1',
                    200 => '#ffc7c7',
                    300 => '#ffa0a0',
                    400 => '#ff6b6b',
                    500 => '#ff3b38',
                    600 => '#ee2d2c',
                    700 => '#c41e1a',
                    800 => '#a11b18',
                    900 => '#851b18',
                    950 => '#480908',
                ],
                'danger' => [
                    50 => '#fff1f1',
                    100 => '#ffe1e1',
                    200 => '#ffc7c7',
                    300 => '#ffa0a0',
                    400 => '#ff6b6b',
                    500 => '#ff3b38',
                    600 => '#ee2d2c',
                    700 => '#c41e1a',
                    800 => '#a11b18',
                    900 => '#851b18',
                    950 => '#480908',
                ],
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Main')
                    ->url(fn (): string => config('app.url'))
                    ->icon('heroicon-o-link'),
            ])

            ->brandLogo(fn () => view('filament.admin.logo'))
            ->darkModeBrandLogo(fn () => view('filament.admin.dark-logo'))
            ->brandLogoHeight('3rem')
            ->darkMode(false)
            ->viteTheme('resources/css/filament/manage/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\UpcomingBirthdaysWidget::class,
                \App\Filament\Widgets\PendingRideablesWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                ->label('Athlete Management'),
                NavigationGroup::make()
                    ->label('User Management'),
            ])
            ->navigationItems([
                NavigationItem::make('Roles')
                    ->url('/manage/shield/roles')
                    ->icon('heroicon-o-shield-check')
                    ->group('User Management')
                    ->sort(2),
            ]);
    }
}
