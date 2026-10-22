<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth"
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }"
    :class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteTitle = setting('site_title', 'Entu Creative — Graphic Designer & Art Director');
        $siteTagline = setting('tagline', 'Bold visual identities and thoughtful packaging design.');
        $pageTitle = isset($title) && filled($title) ? $title . ' — ' . config('app.name', 'Entu Creative') : $siteTitle;
        $metaDescription = $description ?? $siteTagline;
        $canonicalUrl = $canonical ?? url()->current();
        $ogImage = $image ?? asset('images/logo.svg');
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Entu Creative') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.svg') }}">

    <!-- Google Fonts: Syne (Display) & Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Anti-FOUT Dark Mode Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-[#fbfbf9] text-[#17171a] dark:bg-[#0d0d0f] dark:text-[#f4f4f5] font-sans antialiased selection:bg-amber-500 selection:text-white transition-colors duration-300 min-h-screen flex flex-col">
    <!-- Subtle Custom Cursor (fine pointer devices only) -->
    <div id="custom-cursor" class="custom-cursor-ring" aria-hidden="true"></div>
    <div id="custom-cursor-dot" class="custom-cursor-dot" aria-hidden="true"></div>

    <!-- Navigation Header -->
    <x-navbar />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-grow focus:outline-none">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-footer />

    {{ $scripts ?? '' }}
    @stack('scripts')
</body>
</html>
