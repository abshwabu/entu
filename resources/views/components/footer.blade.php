@php
    $email = setting('email', 'hello@entucreative.com');
    $phone = setting('phone', '+251 911 234 567');
    $location = setting('location', 'Addis Ababa, Ethiopia');
    $tagline = setting('tagline', 'Bold visual identities and thoughtful packaging design.');
    $footerText = setting('footer_text', '© ' . date('Y') . ' Entu Creative. All rights reserved.');

    $socials = array_filter([
        'Instagram' => setting('instagram'),
        'Behance' => setting('behance'),
        'Dribbble' => setting('dribbble'),
        'LinkedIn' => setting('linkedin'),
        'Facebook' => setting('facebook'),
        'TikTok' => setting('tiktok'),
    ]);
@endphp

<footer class="bg-[#f4f4f0] dark:bg-[#08080a] text-neutral-800 dark:text-neutral-300 border-t border-[#e7e7e2] dark:border-[#222227] transition-colors duration-300 pt-20 pb-12 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Footer Top Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 pb-16 border-b border-[#e2e2dc] dark:border-[#1e1e24]">
            <!-- Col 1: Big Branding Statement -->
            <div class="lg:col-span-6 space-y-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group" aria-label="Entu Creative Home">
                    <span class="w-8 h-8 rounded-md bg-amber-600 flex items-center justify-center text-white font-display font-bold text-base">
                        E
                    </span>
                    <span class="font-display font-bold text-xl tracking-tight text-neutral-900 dark:text-white">
                        Entu <span class="text-amber-600 font-light text-base tracking-widest uppercase">Creative</span>
                    </span>
                </a>

                <p class="font-display text-2xl sm:text-3xl text-neutral-900 dark:text-white font-medium max-w-lg leading-snug">
                    {{ $tagline }}
                </p>

                <div class="pt-2">
                    <a
                        href="mailto:{{ $email }}"
                        class="inline-flex items-center gap-3 text-lg font-display text-amber-600 dark:text-amber-400 hover:underline underline-offset-4 group"
                    >
                        <span>{{ $email }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Navigation Links -->
            <div class="lg:col-span-3 space-y-4">
                <h4 class="text-xs uppercase tracking-widest font-semibold text-neutral-500 dark:text-neutral-400">
                    Navigation
                </h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ url('/#work') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Selected Projects</a></li>
                    <li><a href="{{ url('/#services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Design Services</a></li>
                    <li><a href="{{ url('/#about') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">About & Ethos</a></li>
                    <li><a href="{{ url('/#testimonials') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Client Testimonials</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Start an Inquiry</a></li>
                </ul>
            </div>

            <!-- Col 3: Studio Coordinates & Socials -->
            <div class="lg:col-span-3 space-y-4">
                <h4 class="text-xs uppercase tracking-widest font-semibold text-neutral-500 dark:text-neutral-400">
                    Studio Coordinates
                </h4>
                <div class="space-y-1 text-sm text-neutral-600 dark:text-neutral-400">
                    <p class="font-medium text-neutral-800 dark:text-neutral-200">{{ $location }}</p>
                    <p>{{ $phone }}</p>
                </div>

                @if(count($socials) > 0)
                    <div class="pt-4">
                        <h4 class="text-xs uppercase tracking-widest font-semibold text-neutral-500 dark:text-neutral-400 mb-3">
                            Connect
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($socials as $platform => $url)
                                <a
                                    href="{{ $url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-3 py-1 text-xs rounded-full border border-[#d8d8d2] dark:border-[#2d2d34] hover:border-amber-600 dark:hover:border-amber-500 hover:text-amber-600 dark:hover:text-amber-400 transition-colors"
                                >
                                    {{ $platform }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500 dark:text-neutral-400">
            <p>{!! $footerText !!}</p>

            <div class="flex items-center gap-6">
                <a href="{{ url('/admin') }}" class="hover:text-neutral-800 dark:hover:text-white transition-colors">
                    Admin Access
                </a>
                <button
                    type="button"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors inline-flex items-center gap-1 focus-visible:outline-2 focus-visible:outline-amber-500 rounded-sm"
                    aria-label="Back to top of page"
                >
                    <span>Back to top</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</footer>
