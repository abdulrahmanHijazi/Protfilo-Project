<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = ['en', 'ar'];

        $locale = $request->route('locale')
            ?? $request->query('locale')
            ?? session('locale', config('app.locale', 'en'));

        if (! in_array($locale, $allowed, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
