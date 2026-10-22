<x-app-layout>
    <!-- HERO SECTION -->
    <section class="relative pt-12 pb-24 md:pt-20 md:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl space-y-8">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-mint-500/15 border border-mint-500/30 text-xs font-semibold text-teal-800 dark:text-mint-400">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-mint-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-mint-400"></span>
                    </span>
                    <span>Available for Select Projects & Art Direction</span>
                </div>

                <!-- Big Display Headline -->
                <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-neutral-900 dark:text-white leading-[1.08]">
                    {{ setting('hero_heading', 'Visual Identity & Graphic Design That Commands Attention') }}
                </h1>

                <!-- Subheading / Bio Intro -->
                <p class="text-lg sm:text-2xl text-neutral-600 dark:text-neutral-300 font-normal leading-relaxed max-w-2xl">
                    {{ setting('hero_subheading', 'Hi, I’m Entu — a multidisciplinary graphic designer specializing in brand identity, packaging, and digital art direction.') }}
                </p>

                <!-- CTA Actions -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <x-button href="#work" size="lg" variant="primary">
                        Explore Work
                    </x-button>

                    <x-button href="#contact" size="lg" variant="outline">
                        Start a Conversation
                    </x-button>
                </div>
            </div>

            <!-- Quantitative Studio Metrics -->
            <div class="mt-20 pt-10 border-t border-[#e7e7e2] dark:border-[#222226] grid grid-cols-2 sm:grid-cols-4 gap-8">
                <div>
                    <span class="block font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white">7+</span>
                    <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400 font-medium">Years Experience</span>
                </div>
                <div>
                    <span class="block font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white">40+</span>
                    <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400 font-medium">Completed Projects</span>
                </div>
                <div>
                    <span class="block font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white">100%</span>
                    <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400 font-medium">Original Craft</span>
                </div>
                <div>
                    <span class="block font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white">Addis</span>
                    <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400 font-medium">Based Worldwide</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SELECTED WORK SECTION -->
    <section id="work" class="py-24 bg-paper dark:bg-panel border-y border-[#e7e7e2] dark:border-panel-soft transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 gap-6">
                <x-section-heading
                    tag="Selected Work"
                    title="Crafted with purpose, engineered to inspire."
                    description="A curated selection of brand identities, packaging suites, and visual systems designed for forward-thinking clients."
                    class="mb-0"
                />

                <div class="shrink-0">
                    <x-button href="{{ url('/#contact') }}" variant="outline" size="sm">
                        Request Full Portfolio
                    </x-button>
                </div>
            </div>

            <!-- Project Grid -->
            @if($featuredProjects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredProjects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 text-neutral-500">
                    No projects published yet. Check back soon!
                </div>
            @endif
        </div>
    </section>

    <!-- SERVICES / CAPABILITIES SECTION -->
    <section id="services" class="py-24 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                tag="Capabilities"
                title="Strategic design solutions for ambitious brands."
                description="From initial concept to tangible production, every discipline is approached with research, nuance, and craftsmanship."
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $index => $service)
                    <div
                        class="p-8 sm:p-10 rounded-3xl bg-paper-raised dark:bg-panel-soft border border-black/10 dark:border-silk-aqua/20 flex flex-col justify-between space-y-6 hover:border-mint-400/50 hover:shadow-lg transition-all duration-300"
                        x-data="{ shown: false }"
                        x-intersect.margin.-80px="shown = true"
                        :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-8': !shown }"
                        class="transition-all duration-700 ease-out"
                    >
                        <div class="space-y-4">
                            <span class="w-12 h-12 rounded-2xl bg-mint-500/15 text-teal-800 dark:text-mint-400 flex items-center justify-center font-display font-bold text-lg border border-mint-500/30">
                                0{{ $index + 1 }}
                            </span>
                            <h3 class="font-display font-bold text-2xl text-neutral-900 dark:text-white">
                                {{ $service->title }}
                            </h3>
                            <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">
                                {{ $service->description }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[#e2e2dc] dark:border-panel-soft">
                            <a
                                href="{{ url('/#contact') }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-teal-700 dark:text-mint-400 hover:underline group focus:outline-none"
                            >
                                <span>Inquire about this</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="py-24 bg-paper dark:bg-panel border-y border-[#e7e7e2] dark:border-panel-soft transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Bio Portrait -->
                <div class="lg:col-span-5 relative" x-data="{ shown: false }" x-intersect.margin.-80px="shown = true" :class="{ 'opacity-100 scale-100': shown, 'opacity-0 scale-95': !shown }" class="transition-all duration-700">
                    @php
                        $bioPhoto = $setting?->getFirstMedia('bio_photo');
                        $bioUrl = $bioPhoto ? $bioPhoto->getUrl() : null;
                    @endphp

                    <div class="relative mx-auto max-w-md rounded-3xl overflow-hidden aspect-[4/5] bg-neutral-200 dark:bg-panel-soft shadow-2xl border-4 border-white dark:border-panel-soft">
                        @if($bioUrl)
                            <img src="{{ $bioUrl }}" alt="Entu Creative portrait" class="w-full h-full object-cover" loading="lazy" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-panel to-panel-deep text-white text-center">
                                <span class="font-display font-extrabold text-4xl mb-2 text-mint-400">ENTU</span>
                                <span class="text-xs uppercase tracking-widest text-neutral-400">Graphic Designer & Art Director</span>
                            </div>
                        @endif

                        <div class="absolute bottom-4 left-4 right-4 p-4 rounded-2xl bg-panel-deep/80 backdrop-blur-md border border-white/10 text-white text-xs flex justify-between items-center">
                            <div>
                                <p class="font-bold text-sm">Entu</p>
                                <p class="text-neutral-300">Addis Ababa, Ethiopia</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-mint-500 text-panel-deep font-bold text-[10px] uppercase tracking-wider">
                                Creative Lead
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bio Narrative Text -->
                <div class="lg:col-span-7 space-y-6">
                    <x-section-heading
                        tag="About Entu"
                        title="{{ setting('about_heading', 'Designing with Purpose, Crafting with Heart') }}"
                        class="mb-6"
                    />

                    <div class="prose prose-neutral dark:prose-invert max-w-none text-base sm:text-lg text-neutral-700 dark:text-neutral-300 leading-relaxed space-y-4">
                        {!! setting('about_body') !!}
                    </div>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        @php
                            $cvMedia = $setting?->getFirstMedia('cv');
                            $cvUrl = $cvMedia ? $cvMedia->getUrl() : null;
                        @endphp

                        @if($cvUrl)
                            <x-button href="{{ $cvUrl }}" target="_blank" variant="secondary" size="md">
                                Download Curriculum Vitae (PDF)
                            </x-button>
                        @endif

                        <x-button href="#contact" variant="primary" size="md">
                            Work Together
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="testimonials" class="py-24 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                tag="Kind Words"
                title="Trusted by visionary founders & studios."
                description="Honest reflections from collaborators on process, speed, and creative results."
                align="center"
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <x-testimonial-card :testimonial="$testimonial" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- CONTACT BANNER SECTION -->
    <section id="contact" class="py-24 bg-silk text-white transition-colors duration-300 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-8 p-8 sm:p-12 rounded-3xl bg-panel/85 backdrop-blur-md border border-silk-aqua/25 shadow-2xl">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-mint-500/20 border border-mint-500/30 text-xs font-semibold uppercase tracking-wider text-mint-400">
                    Let's Build Something Memorable
                </span>

                <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight text-white">
                    Have a vision for your brand? Let’s bring it to life.
                </h2>

                <p class="text-base sm:text-lg text-neutral-200 font-normal max-w-xl mx-auto leading-relaxed">
                    Now booking new branding identities, packaging systems, and bespoke design commissions for upcoming quarters.
                </p>

                <div class="pt-4 flex flex-wrap justify-center items-center gap-4">
                    <x-button
                        href="mailto:{{ setting('email', 'hello@entucreative.com') }}"
                        size="lg"
                        variant="primary"
                    >
                        Send an Email Directly
                    </x-button>

                    <x-button
                        href="{{ url('/#contact') }}"
                        size="lg"
                        variant="outline"
                        class="border-white/40 text-white hover:border-mint-400 hover:text-mint-400"
                    >
                        {{ setting('phone', '+251 911 234 567') }}
                    </x-button>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
