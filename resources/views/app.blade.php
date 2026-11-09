@php($themeMode = \App\Support\Theme::defaultMode())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="{{ $themeMode === 'light' ? '' : 'dark' }}" data-theme-default="{{ $themeMode }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Portfolio') }}</title>

    {{-- SEO meta (hydrated from Inertia props where available) --}}
    <meta name="description" content="Premium portfolio — projects, services, skills, certificates and contact.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', 'Portfolio') }}">
    <meta property="og:description" content="Premium portfolio — projects, services, skills, certificates and contact.">
    <meta name="theme-color" content="{{ \App\Support\Theme::colors()['theme_dark_bg'] }}">
    <meta property="og:image" content="{{ asset('brand/logo-512.png') }}">

    {{-- Gold gem logo --}}
    <link rel="icon" href="{{ asset('brand/favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('brand/apple-touch-icon.png') }}">

    {{-- Fonts: Inter (EN) + Cairo/Tajawal (AR) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&family=Cairo:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <script>
        // Visitor's saved choice wins; otherwise the default mode from Site Settings → Appearance.
        (function () {
            var root = document.documentElement, mode = root.dataset.themeDefault;
            try { mode = localStorage.getItem('theme') || mode; } catch (e) {}
            var dark = mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            root.classList.toggle('dark', dark);
        })();
    </script>

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Brand colors from Site Settings → Appearance (after app.css so they win). --}}
    <style id="theme-colors">{!! \App\Support\Theme::css() !!}</style>
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
