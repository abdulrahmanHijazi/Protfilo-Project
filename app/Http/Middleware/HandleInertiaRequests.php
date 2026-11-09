<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $locale = app()->getLocale();
        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'en';
        }

        return [
            ...parent::share($request),
            'locale' => $locale,
            'dir' => $locale === 'ar' ? 'rtl' : 'ltr',
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
            ],
        ];
    }
}
