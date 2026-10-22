<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth"
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

        $defaultPersonSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => setting('site_title', 'Entu Creative'),
            'jobTitle' => 'Graphic Designer & Art Director',
            'url' => url('/'),
            'email' => 'mailto:' . setting('email', 'hello@entucreative.com'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => setting('location', 'Addis Ababa, Ethiopia'),
            ],
            'sameAs' => array_values(array_filter([
                setting('behance'),
                setting('dribbble'),
                setting('instagram'),
                setting('linkedin'),
                setting('facebook'),
                setting('tiktok'),
            ])),
        ];
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

    <!-- Google Fonts: Syne (Display) & Plus Jakarta Sans (Body) with font-display: swap and preloading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Syne:wght@500;600;700;800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Anti-FOUT Dark Mode Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Analytics Integration (Conditional on settings) -->
    @if($analyticsId = setting('analytics_id'))
        @if(str_starts_with($analyticsId, 'G-') || str_starts_with($analyticsId, 'UA-'))
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $analyticsId }}');
            </script>
        @else
            <!-- Plausible Analytics -->
            <script defer data-domain="{{ $analyticsId }}" src="https://plausible.io/js/script.js"></script>
        @endif
    @endif

    <!-- JSON-LD Structured Data Schema -->
    @if(isset($jsonLd) && filled($jsonLd))
        <script type="application/ld+json">
            {!! is_array($jsonLd) ? json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : $jsonLd !!}
        </script>
    @else
        <script type="application/ld+json">
            {!! json_encode($defaultPersonSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-[#fbfbf9] text-[#17171a] dark:bg-[#0d0d0f] dark:text-[#f4f4f5] font-sans antialiased selection:bg-amber-500 selection:text-white transition-colors duration-300 min-h-screen flex flex-col">
    <!-- Non-JavaScript Fallback Notice -->
    <noscript>
        <div class="bg-amber-600 text-white text-xs font-semibold py-2.5 px-4 text-center">
            JavaScript is disabled in your browser. All portfolio artwork, case studies, and contact details remain fully accessible.
        </div>
    </noscript>

    <!-- Subtle Custom Cursor (fine pointer devices only) -->
    <div id="custom-cursor" class="custom-cursor-ring" aria-hidden="true"></div>
    <div id="custom-cursor-dot" class="custom-cursor-dot" aria-hidden="true"></div>

    <!-- Navigation Header -->
    <x-navbar />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-grow focus:outline-none">
        {{ $slot }}
    </main>

    <!-- Floating Back to Top Button -->
    <button
        type="button"
        x-data="{ showTop: false }"
        @scroll.window="showTop = (window.pageYOffset > 350)"
        x-show="showTop"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-6 right-6 z-40 p-3 rounded-full bg-neutral-900 text-white dark:bg-white dark:text-neutral-950 shadow-xl border border-neutral-700/30 hover:bg-amber-600 dark:hover:bg-amber-400 dark:hover:text-neutral-950 hover:scale-105 transition-all duration-200 focus-visible:outline-2 focus-visible:outline-amber-500"
        aria-label="Back to top of page"
        style="display: none;"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
    </button>

    <!-- Footer -->
    <x-footer />

    {{ $scripts ?? '' }}
    @stack('scripts')
</body>
</html>
