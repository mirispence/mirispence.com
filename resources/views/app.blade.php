<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        @php
            $seo = \Inertia\Inertia::getShared('seo');
            if ($seo instanceof \App\Support\Seo\SeoPayload) {
                $seo = $seo->toArray();
            }
        @endphp

        <title inertia>{{ $seo['title'] ?? config('app.name', 'Laravel') }}</title>
        
        @if($seo)
            <meta name="description" content="{{ $seo['description'] ?? '' }}">
            <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
            <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">
            
            @if(isset($seo['og']))
                <meta property="og:title" content="{{ $seo['og']['title'] ?? ($seo['title'] ?? '') }}">
                <meta property="og:description" content="{{ $seo['og']['description'] ?? ($seo['description'] ?? '') }}">
                <meta property="og:image" content="{{ $seo['og']['image'] ?? '' }}">
                <meta property="og:type" content="{{ $seo['og']['type'] ?? 'website' }}">
                <meta property="og:url" content="{{ $seo['og']['url'] ?? url()->current() }}">
                <meta property="og:site_name" content="{{ $seo['og']['site_name'] ?? config('app.name') }}">
            @endif

            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ $seo['twitter']['title'] ?? ($seo['title'] ?? '') }}">
            <meta name="twitter:description" content="{{ $seo['twitter']['description'] ?? ($seo['description'] ?? '') }}">
            <meta name="twitter:image" content="{{ $seo['twitter']['image'] ?? ($seo['og']['image'] ?? '') }}">
        @endif

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @php
            $isAdmin = auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'));
            $isAdminPath = request()->is('admin*') || request()->is('dashboard*');
            $entryPoint = ($isAdmin || $isAdminPath) ? 'resources/js/admin.ts' : 'resources/js/app.ts';
        @endphp

        @vite($entryPoint)

        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
