<x-app-layout
    title="Design Services & Capabilities — Entu Creative"
    description="Explore comprehensive brand identity systems, sensory packaging design, and digital art direction services by Entu Creative."
>
    <div class="py-12 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs / Back Link -->
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

            <!-- Page Header -->
            <div class="mb-16 sm:mb-24 text-center max-w-3xl mx-auto">
                <span class="inline-block text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 mb-3">
                    Disciplines & Creative Practice
                </span>
                <h1 class="font-display font-extrabold text-4xl sm:text-6xl text-ink dark:text-paper tracking-tight mb-6">
                    Design Services Built for Distinction
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400 text-base sm:text-lg leading-relaxed">
                    Thoughtfully crafted brand identities, sensory packaging systems, and evocative art direction for visionary brands worldwide.
                </p>
            </div>

            <!-- Detailed Services Cards -->
            <div class="space-y-12 sm:space-y-16 mb-24 sm:mb-32">
                @foreach($services as $index => $service)
                    @php
                        $deliverables = match($index) {
                            0 => [
                                'Primary Logo, Sub-marks & Monograms',
                                'Typography Hierarchy & Typeface Pairing',
                                'Curated Color Palette & Chromatic Rules',
                                'Comprehensive Brand Guidelines Book (PDF)',
                                'Social Media Avatar, Banner & Template Kit',
                                'Full Press & Digital Vector Master Library',
                            ],
                            1 => [
                                'Custom Structural Box & Label Dielines',
                                'Substrate, Paperweight & Texture Consultation',
                                'Specialty Finishes (Foil Stamping, Embossing, UV)',
                                '3D Photorealistic Renderings & Pack Shots',
                                'Print Production Management & Press Checks',
                                'Regulatory, Nutritional & Barcode Preparation',
                            ],
                            default => [
                                'Creative Campaign Concepting & Moodboards',
                                'Custom Vector Illustration & Icon Sets',
                                'Editorial Booklets, Zines & Poster Systems',
                                'Digital Advertising & Social Visual Assets',
                                'Art Direction for Photography & Styling',
                                'Exhibition & Spatial Wayfinding Graphics',
                            ],
                        };
                    @endphp

                    <div
                        class="p-8 sm:p-14 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 hover:border-brand-400/50 transition-all duration-300 shadow-sm hover:shadow-xl"
                        x-data="{ shown: false }"
                        x-intersect.margin.-80px="shown = true"
                        :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-8': !shown }"
                        class="transition-all duration-700 ease-out"
                    >
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                            <!-- Left: Number & Title & Description -->
                            <div class="lg:col-span-6 space-y-6">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-brand-400/15 text-brand-800 dark:text-brand-400 flex items-center justify-center font-mono font-bold text-sm border border-brand-400/25">
                                        0{{ $loop->iteration }}
                                    </span>
                                    <span class="text-xs font-mono uppercase tracking-widest text-neutral-400 dark:text-neutral-500">
                                        Signature Offering
                                    </span>
                                </div>

                                <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-ink dark:text-paper tracking-tight leading-tight">
                                    {{ $service->title }}
                                </h2>

                                <p class="text-neutral-600 dark:text-neutral-300 text-base sm:text-lg leading-relaxed">
                                    {{ $service->description }}
                                </p>

                                <div class="pt-4">
                                    <a
                                        href="{{ route('contact', ['subject' => $service->title]) }}"
                                        class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-brand-400 text-ink hover:bg-brand-500 transition-colors shadow-xs"
                                    >
                                        <span>Inquire About This Service</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Right: Deliverables Checklist -->
                            <div class="lg:col-span-6 bg-paper dark:bg-ink-raised p-6 sm:p-8 rounded-2xl border border-black/10 dark:border-white/10">
                                <h3 class="text-xs font-mono font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-6 flex items-center justify-between">
                                    <span>Typical Deliverables</span>
                                    <span class="text-brand-800 dark:text-brand-400">Included Scope</span>
                                </h3>

                                <ul class="space-y-4">
                                    @foreach($deliverables as $deliverable)
                                        <li class="flex items-start gap-3 text-sm text-neutral-700 dark:text-neutral-300">
                                            <svg class="w-5 h-5 text-brand-800 dark:text-brand-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            <span class="font-medium">{{ $deliverable }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Methodology & Process Section -->
            <section class="mb-24 sm:mb-32">
                <div class="p-8 sm:p-14 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 space-y-12">
                    <div class="text-center max-w-2xl mx-auto">
                        <span class="text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 block mb-2">
                            The Methodology
                        </span>
                        <h2 class="font-display font-bold text-2xl sm:text-4xl text-ink dark:text-paper">
                            How We Bring Ideas to Life
                        </h2>
                        <p class="mt-3 text-sm sm:text-base text-neutral-600 dark:text-neutral-400">
                            A structured, highly collaborative four-phase journey ensuring transparency, creative rigor, and precision.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($processSteps as $step)
                            <div class="p-6 rounded-2xl bg-paper dark:bg-ink-raised border border-black/10 dark:border-white/10 space-y-4">
                                <span class="font-mono text-2xl font-bold text-brand-800 dark:text-brand-400">
                                    {{ $step['step'] }}
                                </span>
                                <h3 class="font-display font-bold text-lg text-ink dark:text-paper">
                                    {{ $step['title'] }}
                                </h3>
                                <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">
                                    {{ $step['description'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Bottom CTA Band -->
            <section class="rounded-3xl bg-brand-aurora text-white p-10 sm:p-16 border border-white/10 text-center relative overflow-hidden">
                <div class="max-w-2xl mx-auto space-y-6 relative z-10">
                    <span class="text-xs font-mono uppercase tracking-widest text-brand-400 block">Take the Next Step</span>
                    <h2 class="font-display font-extrabold text-3xl sm:text-5xl tracking-tight leading-tight">
                        Ready to build something unforgettable?
                    </h2>
                    <p class="text-neutral-300 text-sm sm:text-base leading-relaxed">
                        Whether launching a brand identity from scratch or redesigning packaging for global distribution, let's discuss your timeline and vision.
                    </p>
                    <div class="pt-4 flex flex-wrap justify-center gap-4">
                        <a
                            href="{{ route('contact') }}"
                            class="px-8 py-4 rounded-full text-xs font-semibold uppercase tracking-wider bg-brand-400 hover:bg-brand-500 text-ink font-bold shadow-lg transition-all"
                        >
                            Start a Project &rarr;
                        </a>
                        <a
                            href="{{ route('work.index') }}"
                            class="px-8 py-4 rounded-full text-xs font-semibold uppercase tracking-wider border border-white/30 text-white hover:bg-white/10 transition-all"
                        >
                            Browse Work
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
