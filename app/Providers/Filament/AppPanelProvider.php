<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
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
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->darkModeBrandLogo(fn () => view('filament.admin.dark-logo'))
            ->brandLogoHeight('3rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
