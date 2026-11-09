<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch
                ->locales(['en', 'ar'])
                ->labels(['en' => 'English', 'ar' => 'العربية'])
                ->flags([
                    'en' => 'https://flagcdn.com/w40/us.png',
                    'ar' => 'https://flagcdn.com/w40/sa.png',
                ])
                ->displayLocale('en')
                ->visible(insidePanels: true, outsidePanels: false);
        });
    }
}
