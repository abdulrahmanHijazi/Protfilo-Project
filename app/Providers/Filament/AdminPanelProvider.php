<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Outerweb\FilamentTranslatableFields\TranslatableFieldsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors(fn (): array => [
                'primary' => Color::hex(\App\Support\Theme::colors()['theme_accent']),
            ])
            ->brandName(function (): string {
                try {
                    $settings = app(\App\Settings\SiteSettings::class);
                    $title = $settings->seo_title[app()->getLocale()] ?? $settings->seo_title['en'] ?? null;

                    return filled($title) ? (string) $title : config('app.name');
                } catch (\Throwable) {
                    return config('app.name');
                }
            })
            ->brandLogo(asset('brand/logo-horizontal-light.svg'))
            ->darkModeBrandLogo(asset('brand/logo-horizontal-dark.svg'))
            ->brandLogoHeight('2.4rem')
            ->favicon(asset('brand/favicon.svg'))
            ->defaultThemeMode(ThemeMode::Dark)
            ->navigationGroups([
                NavigationGroup::make()->label('Portfolio'),
                NavigationGroup::make()->label('Profile & Settings'),
                NavigationGroup::make()->label('Settings & Security'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Settings & Security'),
                TranslatableFieldsPlugin::make()
                    ->supportedLocales(['en' => 'English', 'ar' => 'Arabic'])
                    ->defaultLocale('en'),
            ]);
    }
}
