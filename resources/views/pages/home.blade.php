<x-app-layout>
    <!-- 1. HERO SECTION -->
    <section class="relative pt-12 pb-20 md:pt-20 md:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left: Headline & Actions -->
                <div class="lg:col-span-7 space-y-8 z-10">
                    <!-- Status Badge -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-xs font-semibold text-amber-700 dark:text-amber-400">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <span>Available for Select Projects 2026</span>
                    </div>

                    <!-- Heading from Settings -->
                    <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-neutral-900 dark:text-white leading-[1.08]">
                        {{ setting('hero_heading', 'Visual Identity & Graphic Design That Commands Attention') }}
                    </h1>

                    <!-- Subheading from Settings -->
                    <p class="text-lg sm:text-2xl text-neutral-600 dark:text-neutral-300 font-normal leading-relaxed max-w-2xl">
                        {{ setting('hero_subheading', 'Hi, I’m Entu — a multidisciplinary graphic designer specializing in brand identity, packaging, and digital art direction.') }}
                    </p>

                    <!-- CTAs -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <x-button href="#work" size="lg" variant="primary">
                            View my work
                        </x-button>

                        <x-button href="#contact" size="lg" variant="outline">
                            Get in touch
                        </x-button>
                    </div>

                    <!-- Studio Metrics -->
                    <div class="pt-8 grid grid-cols-3 gap-6 border-t border-[#e7e7e2] dark:border-[#222226] max-w-lg">
                        <div>
                            <span class="block font-display text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-white">7+</span>
                            <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Years Craft</span>
                        </div>
                        <div>
                            <span class="block font-display text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-white">40+</span>
                            <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Identities</span>
                        </div>
                        <div>
                            <span class="block font-display text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-white">100%</span>
                            <span class="text-xs uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Bespoke</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Animated Featured Visual (Rotating Collage of Project Covers) -->
                <div class="lg:col-span-5 relative"
                     x-data="{
                        activeSlide: 0,
                        totalSlides: {{ max(1, count($heroProjects)) }},
                        timer: null,
                        startRotation() {
                            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && this.totalSlides > 1) {
                                this.timer = setInterval(() => {
                                    this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                                }, 4500);
                            }
                        },
                        stopRotation() {
                            if (this.timer) clearInterval(this.timer);
                        }
                     }"
                     x-init="startRotation()"
                     @mouseenter="stopRotation()"
                     @mouseleave="startRotation()"
                >
                    <div class="relative mx-auto w-full max-w-md lg:max-w-none h-[420px] sm:h-[480px]">
                        <!-- Decorative ambient glow -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-amber-500/20 to-neutral-500/10 rounded-3xl blur-2xl -z-10" aria-hidden="true"></div>

                        @foreach($heroProjects as $idx => $hp)
                            @php
                                $hpCover = $hp->getFirstMedia('cover');
                                $hpCoverUrl = $hpCover ? $hpCover->getUrl('large') : ($hpCover ? $hpCover->getUrl() : null);
                            @endphp

                            <div
                                x-show="activeSlide === {{ $idx }}"
                                x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-500"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
                                class="absolute inset-0 rounded-3xl overflow-hidden bg-neutral-900 shadow-2xl border border-white/20 dark:border-white/10 group cursor-pointer"
                                style="display: {{ $idx === 0 ? 'block' : 'none' }};"
                            >
                                @if($hpCoverUrl)
                                    <img
                                        src="{{ $hpCoverUrl }}"
                                        alt="{{ $hp->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                                    />
                                @else
                                    <div class="w-full h-full bg-neutral-800 flex items-center justify-center text-white">
                                        <span class="font-display font-bold text-xl">{{ $hp->title }}</span>
                                    </div>
                                @endif

                                <!-- Project Overlay Pill -->
                                <div class="absolute inset-x-4 bottom-4 p-4 rounded-2xl bg-black/65 backdrop-blur-md border border-white/10 text-white flex items-center justify-between">
                                    <div class="space-y-0.5 max-w-[70%]">
                                        <span class="text-[10px] uppercase font-bold tracking-widest text-amber-400">
                                            {{ $hp->category?->name ?? 'Featured Project' }}
                                        </span>
                                        <h3 class="font-display font-bold text-sm sm:text-base truncate">
                                            {{ $hp->title }}
                                        </h3>
                                    </div>

                                    <a
                                        href="#work"
                                        class="p-2 rounded-full bg-white/20 text-white hover:bg-amber-600 transition-colors"
                                        aria-label="View {{ $hp->title }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slide Dots Controller -->
                    @if(count($heroProjects) > 1)
                        <div class="mt-4 flex items-center justify-center gap-2">
                            @foreach($heroProjects as $idx => $hp)
                                <button
                                    type="button"
                                    @click="activeSlide = {{ $idx }}"
                                    class="h-1.5 transition-all duration-300 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                                    :class="activeSlide === {{ $idx }} ? 'w-8 bg-amber-600 dark:bg-amber-400' : 'w-2 bg-neutral-300 dark:bg-neutral-700 hover:bg-neutral-400'"
                                    aria-label="Switch to featured project slide {{ $idx + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SELECTED WORK (Asymmetric Masonry Grid) -->
    <section id="work" class="py-24 bg-white dark:bg-[#111114] border-y border-[#e7e7e2] dark:border-[#222226] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 gap-6">
                <x-section-heading
                    tag="Selected Work"
                    title="Crafted with purpose, engineered to inspire."
                    description="A curated showcase of up to 6 signature projects spanning brand identity, custom packaging, and art direction."
                    class="mb-0"
                />

                <div class="shrink-0">
                    <a
                        href="{{ route('work.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 group focus-visible:outline-2 focus-visible:outline-amber-500 rounded-sm"
                    >
                        <span>View all work</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Asymmetric Masonry-style Grid -->
            @if($featuredProjects->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    @foreach($featuredProjects as $index => $project)
                        @php
                            // Asymmetric layout rhythm:
                            // 0: wide (8 cols)
                            // 1: vertical (4 cols)
                            // 2: vertical (4 cols)
                            // 3: wide (8 cols)
                            // 4: half (6 cols)
                            // 5: half (6 cols)
                            $colSpans = [
                                0 => 'lg:col-span-8',
                                1 => 'lg:col-span-4',
                                2 => 'lg:col-span-4',
                                3 => 'lg:col-span-8',
                                4 => 'lg:col-span-6',
                                5 => 'lg:col-span-6',
                            ][$index % 6] ?? 'lg:col-span-6';

                            $aspectRatio = [
                                0 => 'aspect-[16/10]',
                                1 => 'aspect-[4/5]',
                                2 => 'aspect-[4/5]',
                                3 => 'aspect-[16/10]',
                                4 => 'aspect-[4/3]',
                                5 => 'aspect-[4/3]',
                            ][$index % 6] ?? 'aspect-[4/3]';
                        @endphp

                        <div class="{{ $colSpans }}">
                            <x-project-card :project="$project" :aspect="$aspectRatio" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 text-neutral-500">
                    No projects found. Use the admin panel to add and publish projects.
                </div>
            @endif
        </div>
    </section>

    <!-- 3. SERVICES SECTION -->
    <section id="services" class="py-24 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                tag="Capabilities"
                title="Design services tailored for distinctive brands."
                description="Holistic design disciplines approached with depth, rigor, and cultural relevance."
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $index => $service)
                    <div
                        class="p-8 sm:p-10 rounded-3xl bg-neutral-100 dark:bg-[#141417] border border-[#e7e7e2] dark:border-[#26262b] flex flex-col justify-between space-y-6 hover:border-amber-500/40 hover:shadow-lg transition-all duration-300"
                        x-data="{ shown: false }"
                        x-intersect.margin.-80px="shown = true"
                        :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-8': !shown }"
                        class="transition-all duration-700 ease-out"
                    >
                        <div class="space-y-5">
                            <div class="flex items-center justify-between">
                                <span class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-display font-bold text-lg border border-amber-500/20">
                                    0{{ $index + 1 }}
                                </span>

                                @if($service->icon)
                                    <span class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                                        {{ $service->icon }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-display font-bold text-2xl text-neutral-900 dark:text-white">
                                {{ $service->title }}
                            </h3>

                            <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">
                                {{ $service->description }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[#e2e2dc] dark:border-[#222226]">
                            <a
                                href="#contact"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 hover:text-amber-700 group focus:outline-none"
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

    <!-- 4. ABOUT TEASER -->
    <section id="about" class="py-24 bg-white dark:bg-[#111114] border-y border-[#e7e7e2] dark:border-[#222226] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Bio Photo -->
                <div class="lg:col-span-5 relative" x-data="{ shown: false }" x-intersect.margin.-80px="shown = true" :class="{ 'opacity-100 scale-100': shown, 'opacity-0 scale-95': !shown }" class="transition-all duration-700">
                    @php
                        $bioPhoto = $setting?->getFirstMedia('bio_photo');
                        $bioUrl = $bioPhoto ? $bioPhoto->getUrl() : null;
                    @endphp

                    <div class="relative mx-auto max-w-md rounded-3xl overflow-hidden aspect-[4/5] bg-neutral-200 dark:bg-neutral-800 shadow-2xl border-4 border-white dark:border-[#1e1e24]">
                        @if($bioUrl)
                            <img src="{{ $bioUrl }}" alt="Entu Creative portrait" class="w-full h-full object-cover" loading="lazy" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-neutral-800 to-neutral-950 text-white text-center">
                                <span class="font-display font-extrabold text-4xl mb-2 text-amber-500">ENTU</span>
                                <span class="text-xs uppercase tracking-widest text-neutral-400">Graphic Designer & Art Director</span>
                            </div>
                        @endif

                        <div class="absolute bottom-4 left-4 right-4 p-4 rounded-2xl bg-black/60 backdrop-blur-md border border-white/10 text-white text-xs flex justify-between items-center">
                            <div>
                                <p class="font-bold text-sm">Entu</p>
                                <p class="text-neutral-300">{{ setting('location', 'Addis Ababa, Ethiopia') }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-amber-500 text-neutral-950 font-semibold text-[10px] uppercase tracking-wider">
                                Independent
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Short Text & Narrative Teaser -->
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
                                Download CV (PDF)
                            </x-button>
                        @endif

                        <x-button href="#contact" variant="primary" size="md">
                            Start a Conversation
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. TESTIMONIALS SLIDER (Alpine Slider) -->
    <section id="testimonials" class="py-24 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading
                tag="Testimonials"
                title="What clients say about our partnership."
                description="Collaborative experiences from founders, art directors, and brand leads."
                align="center"
            />

            @if($testimonials->count() > 0)
                <div
                    class="relative max-w-4xl mx-auto"
                    x-data="{
                        current: 0,
                        total: {{ $testimonials->count() }},
                        timer: null,
                        next() {
                            this.current = (this.current + 1) % this.total;
                        },
                        prev() {
                            this.current = (this.current - 1 + this.total) % this.total;
                        },
                        startAuto() {
                            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && this.total > 1) {
                                this.timer = setInterval(() => this.next(), 6000);
                            }
                        },
                        stopAuto() {
                            if (this.timer) clearInterval(this.timer);
                        }
                    }"
                    x-init="startAuto()"
                    @mouseenter="stopAuto()"
                    @mouseleave="startAuto()"
                    @keydown.right="next()"
                    @keydown.left="prev()"
                    tabindex="0"
                    aria-label="Client Testimonials Slider"
                    class="focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 rounded-3xl"
                >
                    <!-- Slider Viewport -->
                    <div class="relative min-h-[320px] sm:min-h-[280px]">
                        @foreach($testimonials as $index => $t)
                            <div
                                x-show="current === {{ $index }}"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 translate-x-8"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100 translate-x-0"
                                x-transition:leave-end="opacity-0 -translate-x-8"
                                class="w-full"
                                style="display: {{ $index === 0 ? 'block' : 'none' }};"
                            >
                                <x-testimonial-card :testimonial="$t" class="mx-auto" />
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Controls -->
                    @if($testimonials->count() > 1)
                        <div class="mt-8 flex items-center justify-between">
                            <!-- Prev Button -->
                            <button
                                type="button"
                                @click="prev()"
                                class="p-3 rounded-full border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-200 hover:bg-neutral-200/60 dark:hover:bg-neutral-800 transition-colors focus-visible:outline-2 focus-visible:outline-amber-500"
                                aria-label="Previous testimonial"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Dot Indicators -->
                            <div class="flex items-center gap-2">
                                @foreach($testimonials as $index => $t)
                                    <button
                                        type="button"
                                        @click="current = {{ $index }}"
                                        class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                                        :class="current === {{ $index }} ? 'w-8 bg-amber-600 dark:bg-amber-400' : 'w-2 bg-neutral-300 dark:bg-neutral-700 hover:bg-neutral-400'"
                                        aria-label="Navigate to testimonial {{ $index + 1 }}"
                                    ></button>
                                @endforeach
                            </div>

                            <!-- Next Button -->
                            <button
                                type="button"
                                @click="next()"
                                class="p-3 rounded-full border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-200 hover:bg-neutral-200/60 dark:hover:bg-neutral-800 transition-colors focus-visible:outline-2 focus-visible:outline-amber-500"
                                aria-label="Next testimonial"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- 6. CTA BAND -->
    <section id="contact" class="py-24 bg-neutral-900 text-white dark:bg-[#070709] transition-colors duration-300 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-xs font-semibold uppercase tracking-wider text-amber-400">
                    Let’s Connect
                </span>

                <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight">
                    Let’s create something together.
                </h2>

                <p class="text-base sm:text-lg text-neutral-300 font-normal max-w-xl mx-auto leading-relaxed">
                    Have an upcoming brand launch, packaging redesign, or art direction project? Let’s make it unforgettable.
                </p>

                <div class="pt-4 flex flex-wrap justify-center items-center gap-4">
                    <x-button
                        href="mailto:{{ setting('email', 'hello@entucreative.com') }}"
                        size="lg"
                        variant="white"
                    >
                        Start a Conversation
                    </x-button>

                    <x-button
                        href="tel:{{ setting('phone', '+251911234567') }}"
                        size="lg"
                        variant="outline"
                        class="border-white/30 text-white hover:border-amber-400 hover:text-amber-400"
                    >
                        {{ setting('phone', '+251 911 234 567') }}
                    </x-button>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
