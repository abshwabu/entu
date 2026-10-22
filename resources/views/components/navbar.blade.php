@php
    $siteTitle = setting('site_title', 'Entu Creative');
    $email = setting('email', 'hello@entucreative.com');
@endphp

<header
    x-data="{
        isScrolled: false,
        mobileMenuOpen: false
    }"
    @scroll.window="isScrolled = (window.pageYOffset > 24)"
    class="sticky top-0 z-50 w-full transition-all duration-300"
    :class="{
        'bg-paper/90 dark:bg-ink/90 backdrop-blur-md border-b border-black/10 dark:border-white/10 shadow-xs': isScrolled,
        'bg-transparent border-b border-transparent': !isScrolled
    }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand / Logo -->
            <a
                href="{{ url('/') }}"
                class="flex items-center gap-3 group focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                aria-label="{{ $siteTitle }} Homepage"
            >
                <div class="relative w-9 h-9 rounded-full overflow-hidden shadow-sm group-hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('brand/logo-icon.svg') }}" alt="Entu Creative" class="w-full h-full object-cover" />
                </div>
                <span class="font-display font-bold text-lg sm:text-xl tracking-tight text-ink dark:text-paper flex items-center gap-1.5">
                    <span>Entu</span>
                    <span class="text-brand-800 dark:text-brand-400 font-light text-base uppercase tracking-widest">Creative</span>
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium" aria-label="Primary Navigation">
                <a
                    href="{{ route('work.index') }}"
                    class="text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('work.*') || request()->routeIs('project.*') ? 'text-brand-800 dark:text-brand-400 font-semibold' : '' }} focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    Work
                </a>
                <a
                    href="{{ route('services') }}"
                    class="text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('services') ? 'text-brand-800 dark:text-brand-400 font-semibold' : '' }} focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    Services
                </a>
                <a
                    href="{{ route('about') }}"
                    class="text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('about') ? 'text-brand-800 dark:text-brand-400 font-semibold' : '' }} focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    About
                </a>
                <a
                    href="{{ url('/#testimonials') }}"
                    class="text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 transition-colors focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    Testimonials
                </a>
                <a
                    href="{{ route('contact') }}"
                    class="text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('contact') ? 'text-brand-800 dark:text-brand-400 font-semibold' : '' }} focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    Contact
                </a>
            </nav>

            <!-- Right Actions: Theme Toggle & CTA -->
            <div class="flex items-center gap-3">
                <!-- Dark Mode Toggle Button -->
                <button
                    type="button"
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-full text-neutral-700 dark:text-neutral-300 hover:bg-black/5 dark:hover:bg-white/10 transition-colors focus-visible:outline-2 focus-visible:outline-brand-400"
                    aria-label="Toggle dark mode theme"
                >
                    <!-- Sun icon (shown in dark mode) -->
                    <svg x-show="$store.theme.dark" x-cloak class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg x-show="!$store.theme.dark" x-cloak class="w-5 h-5 text-ink dark:text-paper" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                <!-- Desktop Contact CTA -->
                <a
                    href="{{ route('contact') }}"
                    class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-full bg-brand-400 text-ink hover:bg-brand-500 transition-all duration-200 shadow-xs focus-visible:outline-2 focus-visible:outline-brand-400"
                >
                    Get in Touch
                </a>

                <!-- Mobile Hamburger Button -->
                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-lg text-ink dark:text-paper hover:bg-black/5 dark:hover:bg-white/10 transition-colors focus-visible:outline-2 focus-visible:outline-brand-400"
                    aria-label="Open mobile navigation menu"
                    :aria-expanded="mobileMenuOpen"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Slide-In Navigation Drawer -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="md:hidden fixed inset-x-0 top-20 bg-paper/98 dark:bg-ink/98 backdrop-blur-xl border-b border-black/10 dark:border-white/10 shadow-xl px-6 py-8"
        @click.away="mobileMenuOpen = false"
        style="display: none;"
    >
        <nav class="flex flex-col gap-6 text-lg font-display font-medium" aria-label="Mobile Navigation">
            <a
                href="{{ route('work.index') }}"
                @click="mobileMenuOpen = false"
                class="text-ink dark:text-paper hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('work.*') || request()->routeIs('project.*') ? 'text-brand-800 dark:text-brand-400 font-bold' : '' }}"
            >
                Portfolio & Work
            </a>
            <a
                href="{{ route('services') }}"
                @click="mobileMenuOpen = false"
                class="text-ink dark:text-paper hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('services') ? 'text-brand-800 dark:text-brand-400 font-bold' : '' }}"
            >
                Services
            </a>
            <a
                href="{{ route('about') }}"
                @click="mobileMenuOpen = false"
                class="text-ink dark:text-paper hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('about') ? 'text-brand-800 dark:text-brand-400 font-bold' : '' }}"
            >
                About Entu
            </a>
            <a
                href="{{ url('/#testimonials') }}"
                @click="mobileMenuOpen = false"
                class="text-ink dark:text-paper hover:text-brand-800 dark:hover:text-brand-400 transition-colors"
            >
                Client Testimonials
            </a>
            <a
                href="{{ route('contact') }}"
                @click="mobileMenuOpen = false"
                class="text-ink dark:text-paper hover:text-brand-800 dark:hover:text-brand-400 transition-colors {{ request()->routeIs('contact') ? 'text-brand-800 dark:text-brand-400 font-bold' : '' }}"
            >
                Contact & Inquiries
            </a>

            <div class="pt-6 border-t border-black/10 dark:border-white/10 flex flex-col gap-4">
                <button
                    type="button"
                    @click="$store.theme.toggle()"
                    class="flex items-center justify-between w-full py-2.5 px-4 rounded-xl bg-black/5 dark:bg-ink-soft text-ink dark:text-paper text-sm font-medium transition-colors"
                >
                    <span class="flex items-center gap-2">
                        <svg x-show="$store.theme.dark" x-cloak class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg x-show="!$store.theme.dark" x-cloak class="w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <span x-text="$store.theme.dark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">Switch Theme</span>
                    </span>
                    <span class="text-xs uppercase tracking-wider font-semibold text-neutral-400 dark:text-neutral-500" x-text="$store.theme.dark ? 'Dark' : 'Light'"></span>
                </button>

                <a
                    href="{{ url('/#contact') }}"
                    @click="mobileMenuOpen = false"
                    class="w-full text-center py-3 px-6 rounded-full bg-brand-400 text-ink font-sans text-sm font-semibold hover:bg-brand-500 transition-colors"
                >
                    Start a Project
                </a>
                <a
                    href="{{ url('/admin') }}"
                    class="text-center text-xs text-neutral-500 hover:text-ink dark:hover:text-paper"
                >
                    Admin Portal
                </a>
            </div>
        </nav>
    </div>
</header>
