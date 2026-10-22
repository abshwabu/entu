@php
    $bioPhotoUrl = $bioMedia ? $bioMedia->getUrl() : null;
    $cvUrl = $cvMedia ? $cvMedia->getUrl() : null;
    $aboutHeading = setting('about_heading', 'Designing with Purpose, Crafting with Heart');
    $aboutBody = setting('about_body', '<p>With over 7 years of professional experience, I partner with forward-thinking brands, cultural institutions, and ambitious startups worldwide.</p><p>My approach blends deep contextual research with bold, expressive typography and intentional palettes to produce work that resonates across print and digital mediums.</p>');
    $location = setting('location', 'Addis Ababa, Ethiopia');
    $email = setting('email', 'hello@entucreative.com');
@endphp

<x-app-layout
    title="About Entu Creative — Graphic Designer & Art Director"
    :description="strip_tags($aboutHeading . ' ' . \Illuminate\Support\Str::limit(strip_tags($aboutBody), 120))"
>
    <div class="py-12 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb / Back Link -->
            <div class="mb-10">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-brand-800 dark:text-neutral-400 dark:hover:text-brand-400 transition-colors group focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                >
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>

            <!-- Hero Section: Bio Photo & Story -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start mb-24 sm:mb-32">
                <!-- Left: Bio Portrait Image -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Ambient backdrop glow -->
                        <div class="absolute -inset-4 bg-brand-aurora-subtle rounded-3xl blur-2xl -z-10" aria-hidden="true"></div>

                        <div class="relative rounded-3xl overflow-hidden bg-neutral-200 dark:bg-ink-soft shadow-2xl border border-black/10 dark:border-white/10 aspect-[3/4]">
                            @if($bioPhotoUrl)
                                <img
                                    src="{{ $bioPhotoUrl }}"
                                    alt="Entu — Graphic Designer & Art Director"
                                    width="600"
                                    height="800"
                                    loading="eager"
                                    class="w-full h-full object-cover object-center"
                                />
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-800/30 to-ink-soft p-8 text-center">
                                    <span class="w-20 h-20 rounded-full bg-brand-400 text-ink flex items-center justify-center font-display text-3xl font-extrabold shadow-lg mb-4">E</span>
                                    <span class="font-display font-bold text-xl text-paper">Entu Creative</span>
                                    <span class="text-xs uppercase tracking-widest text-brand-400 mt-1">Addis Ababa, Ethiopia</span>
                                </div>
                            @endif

                            <!-- Overlay badge -->
                            <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-black/60 backdrop-blur-md border border-white/10 text-white flex items-center justify-between">
                                <div>
                                    <h4 class="font-display font-bold text-sm tracking-wide">Entu</h4>
                                    <p class="text-xs text-neutral-300">Graphic Designer & Art Director</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-semibold uppercase tracking-wider bg-brand-400 text-ink">
                                    7+ Yrs Craft
                                </span>
                            </div>
                        </div>

                        <!-- Studio Location Tag -->
                        <div class="mt-6 flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-400 px-2 font-mono">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-800 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span>{{ $location }}</span>
                            </span>
                            <span>GMT+3 (Africa/Addis_Ababa)</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Editorial Bio & Narrative -->
                <div class="lg:col-span-7 space-y-8">
                    <div>
                        <span class="inline-block text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 mb-3">
                            The Designer Behind the Work
                        </span>
                        <h1 class="font-display font-extrabold text-3xl sm:text-5xl lg:text-6xl text-ink dark:text-paper tracking-tight leading-[1.1] mb-6">
                            {{ $aboutHeading }}
                        </h1>
                    </div>

                    <!-- Story from Settings (Rich Text) -->
                    <div class="prose prose-lg dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300 leading-relaxed font-sans prose-headings:font-display prose-headings:font-bold prose-headings:tracking-tight prose-a:text-brand-800 dark:prose-a:text-brand-400 hover:prose-a:underline">
                        {!! $aboutBody !!}
                    </div>

                    <!-- Key Milestones / Studio Metrics -->
                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-black/10 dark:border-white/10">
                        <div>
                            <span class="block font-display text-3xl sm:text-4xl font-bold text-ink dark:text-paper">7+</span>
                            <span class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Years Studio Experience</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl sm:text-4xl font-bold text-ink dark:text-paper">40+</span>
                            <span class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Brand Identities</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl sm:text-4xl font-bold text-ink dark:text-paper">100%</span>
                            <span class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Bespoke Design</span>
                        </div>
                    </div>

                    <!-- Action Buttons: Download CV & Contact -->
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        @if($cvUrl)
                            <a
                                href="{{ $cvUrl }}"
                                download
                                class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-brand-400 text-ink hover:bg-brand-500 shadow-md hover:shadow-xl transition-all duration-300 focus-visible:outline-2 focus-visible:outline-brand-400 font-bold"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.5V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>Download CV / Resume (PDF)</span>
                            </a>
                        @endif

                        <a
                            href="{{ route('contact') }}"
                            class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider border border-brand-500/80 text-brand-800 dark:text-brand-400 hover:bg-brand-400/10 shadow-sm transition-all duration-300 focus-visible:outline-2 focus-visible:outline-brand-400"
                        >
                            <span>Let's Work Together &rarr;</span>
                        </a>
                    </div>

                    <!-- Social Links Bar -->
                    <div class="pt-6 border-t border-black/10 dark:border-white/10">
                        <span class="block text-xs font-mono uppercase tracking-wider text-neutral-400 dark:text-neutral-500 mb-4">
                            Connect Across the Web
                        </span>
                        <div class="flex flex-wrap gap-3">
                            @if(setting('behance'))
                                <a href="{{ setting('behance') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-medium bg-black/5 hover:bg-black/10 dark:bg-white/5 dark:hover:bg-white/10 text-neutral-700 dark:text-neutral-300 transition-colors">
                                    Behance ↗
                                </a>
                            @endif

                            @if(setting('dribbble'))
                                <a href="{{ setting('dribbble') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-medium bg-black/5 hover:bg-black/10 dark:bg-white/5 dark:hover:bg-white/10 text-neutral-700 dark:text-neutral-300 transition-colors">
                                    Dribbble ↗
                                </a>
                            @endif

                            @if(setting('instagram'))
                                <a href="{{ setting('instagram') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-medium bg-black/5 hover:bg-black/10 dark:bg-white/5 dark:hover:bg-white/10 text-neutral-700 dark:text-neutral-300 transition-colors">
                                    Instagram ↗
                                </a>
                            @endif

                            @if(setting('linkedin'))
                                <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-medium bg-black/5 hover:bg-black/10 dark:bg-white/5 dark:hover:bg-white/10 text-neutral-700 dark:text-neutral-300 transition-colors">
                                    LinkedIn ↗
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills & Capabilities Section -->
            <section class="mb-24 sm:mb-32">
                <div class="p-8 sm:p-14 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 space-y-12">
                    <div class="max-w-2xl">
                        <span class="text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 block mb-2">
                            Capabilities & Disciplines
                        </span>
                        <h2 class="font-display font-bold text-2xl sm:text-4xl text-ink dark:text-paper">
                            Holistic Craft Across Mediums
                        </h2>
                        <p class="mt-3 text-sm sm:text-base text-neutral-600 dark:text-neutral-400">
                            Equipped to carry visual narratives from foundational strategy to press-ready execution and digital deployment.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Core Disciplines -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400 border-b border-black/10 dark:border-white/10 pb-2">
                                Design Disciplines
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-medium bg-paper dark:bg-ink-raised text-neutral-800 dark:text-neutral-200 border border-black/10 dark:border-white/10 shadow-xs">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Software & Production Tools -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400 border-b border-black/10 dark:border-white/10 pb-2">
                                Tools & Production Software
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tools as $tool)
                                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-mono font-medium bg-brand-400/15 text-brand-800 dark:text-brand-400 border border-brand-400/25">
                                        {{ $tool }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Services Summary -->
            <section class="mb-24 sm:mb-32">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
                    <div>
                        <span class="text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 block mb-2">
                            What I Offer
                        </span>
                        <h2 class="font-display font-bold text-2xl sm:text-4xl text-ink dark:text-paper">
                            Design Services
                        </h2>
                    </div>
                    <a
                        href="{{ route('services') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-brand-800 dark:text-brand-400 hover:text-brand-900 dark:hover:text-brand-300 group"
                    >
                        <span>View full service details</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($services as $service)
                        <div class="p-8 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 flex flex-col justify-between space-y-6 hover:border-brand-400/50 transition-colors">
                            <div class="space-y-4">
                                <span class="font-mono text-xs text-brand-800 dark:text-brand-400 font-bold">0{{ $loop->iteration }}</span>
                                <h3 class="font-display font-bold text-xl text-ink dark:text-paper">
                                    {{ $service->title }}
                                </h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">
                                    {{ $service->description }}
                                </p>
                            </div>
                            <a
                                href="{{ route('contact', ['subject' => $service->title]) }}"
                                class="text-xs font-semibold uppercase tracking-wider text-brand-800 dark:text-brand-400 hover:underline"
                            >
                                Inquire &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Bottom CTA Banner -->
            <section class="rounded-3xl bg-brand-aurora text-white p-10 sm:p-16 border border-white/10 text-center relative overflow-hidden">
                <div class="max-w-2xl mx-auto space-y-6 relative z-10">
                    <span class="text-xs font-mono uppercase tracking-widest text-brand-400 block">Start a Conversation</span>
                    <h2 class="font-display font-extrabold text-3xl sm:text-5xl tracking-tight leading-tight">
                        Have an ambitious project in mind?
                    </h2>
                    <p class="text-neutral-300 text-sm sm:text-base leading-relaxed">
                        I am currently booking select brand identity, packaging, and art direction projects for the upcoming quarter.
                    </p>
                    <div class="pt-4 flex flex-wrap justify-center gap-4">
                        <a
                            href="{{ route('contact') }}"
                            class="px-8 py-4 rounded-full text-xs font-semibold uppercase tracking-wider bg-brand-400 hover:bg-brand-500 text-ink font-bold shadow-lg transition-all"
                        >
                            Get in Touch &rarr;
                        </a>
                        <a
                            href="{{ route('work.index') }}"
                            class="px-8 py-4 rounded-full text-xs font-semibold uppercase tracking-wider border border-white/30 text-white hover:bg-white/10 transition-all"
                        >
                            Explore Portfolio
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
