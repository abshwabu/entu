@php
    $coverMedia = $project->getFirstMedia('cover');
    $coverLarge = $coverMedia?->hasGeneratedConversion('large') ? $coverMedia->getUrl('large') : $coverMedia?->getUrl();
    $coverThumb = $coverMedia?->hasGeneratedConversion('thumb') ? $coverMedia->getUrl('thumb') : $coverMedia?->getUrl();
    $coverOrig = $coverMedia?->getUrl();
    $coverUrl = $coverLarge ?: $coverOrig;

    $galleryMedia = $project->getMedia('gallery');

    $seoTitle = $project->seo_title ?: $project->title;
    $seoDescription = $project->seo_description ?: ($project->summary ?: \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 160));
    $ogImage = $coverUrl ?: asset('brand/og-image.png');

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'VisualArtwork',
                'name' => $project->title,
                'headline' => $seoTitle,
                'description' => $seoDescription,
                'image' => $ogImage,
                'datePublished' => $project->published_at?->toIso8601String() ?? now()->toIso8601String(),
                'dateModified' => $project->updated_at->toIso8601String(),
                'artMedium' => implode(', ', $project->tools_used ?? ['Digital Graphic Design']),
                'genre' => $project->category?->name ?? 'Graphic Design',
                'creator' => [
                    '@type' => 'Person',
                    'name' => 'Entu Creative',
                    'url' => url('/'),
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_values(array_filter([
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Work',
                        'item' => route('work.index'),
                    ],
                    $project->category ? [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $project->category->name,
                        'item' => route('work.category', $project->category->slug),
                    ] : null,
                    [
                        '@type' => 'ListItem',
                        'position' => $project->category ? 4 : 3,
                        'name' => $project->title,
                        'item' => $project->url,
                    ],
                ])),
            ],
        ],
    ];
@endphp

<x-app-layout
    :title="$seoTitle"
    :description="$seoDescription"
    :image="$ogImage"
    :ogType="'article'"
    :canonical="$project->url"
    :jsonLd="$jsonLd"
>
    <div
        x-data="projectDetail({
            lightboxOpen: false,
            currentIndex: 0,
            touchStartX: 0,
            touchEndX: 0,
            items: {{ Js::from($lightboxItems) }}
        })"
        @keydown.escape.window="if (lightboxOpen) closeLightbox()"
        @keydown.right.window="if (lightboxOpen) next()"
        @keydown.left.window="if (lightboxOpen) prev()"
        class="py-8 sm:py-14"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Admin Preview Mode Banner (Only shown for unpublished items previewed by admins) -->
            @if(!$project->is_published && $canPreview)
                <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-brand-400/15 border border-brand-400/30 text-brand-800 dark:text-brand-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-brand-400/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-800 dark:text-brand-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-bold text-sm">Preview Mode Active</h4>
                            <p class="text-xs text-brand-800/80 dark:text-brand-300">
                                This project is unpublished and hidden from visitors. You are seeing this because you are logged in as an administrator.
                            </p>
                        </div>
                    </div>
                    <a
                        href="/admin/projects/{{ $project->id }}/edit"
                        class="shrink-0 text-xs font-semibold px-4 py-2 rounded-lg bg-brand-400 text-ink hover:bg-brand-500 transition-colors"
                    >
                        Edit in Admin &rarr;
                    </a>
                </div>
            @endif

            <!-- Top Navigation / Breadcrumbs -->
            <div class="mb-10 flex items-center justify-between">
                @if($project->category)
                    <a
                        href="{{ route('work.category', $project->category->slug) }}"
                        class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-brand-800 dark:text-neutral-400 dark:hover:text-brand-400 transition-colors group focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                    >
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Back to {{ $project->category->name }}</span>
                    </a>
                @else
                    <a
                        href="{{ route('work.index') }}"
                        class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-brand-800 dark:text-neutral-400 dark:hover:text-brand-400 transition-colors group focus-visible:outline-2 focus-visible:outline-brand-400 rounded-sm"
                    >
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Back to All Work</span>
                    </a>
                @endif

                <!-- Quick Next/Prev Controls in header -->
                <div class="flex items-center gap-2">
                    @if($prevProject)
                        <a
                            href="{{ $prevProject->url }}"
                            class="p-2 rounded-full border border-black/10 dark:border-white/10 text-neutral-600 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 hover:border-brand-400/50 transition-colors"
                            title="Previous Project: {{ $prevProject->title }}"
                            aria-label="Previous Project: {{ $prevProject->title }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                    @endif

                    @if($nextProject)
                        <a
                            href="{{ $nextProject->url }}"
                            class="p-2 rounded-full border border-black/10 dark:border-white/10 text-neutral-600 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 hover:border-brand-400/50 transition-colors"
                            title="Next Project: {{ $nextProject->title }}"
                            aria-label="Next Project: {{ $nextProject->title }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Case Study Header -->
            <header class="mb-12 sm:mb-16">
                @if($project->category)
                    <div class="mb-4">
                        <a
                            href="{{ route('work.category', $project->category->slug) }}"
                            class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-widest bg-brand-400/15 text-brand-800 dark:text-brand-400 border border-brand-400/25 hover:bg-brand-400/25 transition-colors"
                        >
                            {{ $project->category->name }}
                        </a>
                    </div>
                @endif

                <h1 class="font-display font-extrabold text-4xl sm:text-6xl lg:text-7xl text-ink dark:text-paper tracking-tight leading-[1.08] max-w-5xl mb-6">
                    {{ $project->title }}
                </h1>

                @if($project->summary)
                    <p class="text-xl sm:text-2xl text-neutral-600 dark:text-neutral-300 font-light leading-relaxed max-w-4xl mb-10">
                        {{ $project->summary }}
                    </p>
                @endif

                <!-- Metadata Grid -->
                <div class="p-6 sm:p-8 rounded-3xl bg-neutral-100/80 dark:bg-[#141417] border border-[#e7e7e2] dark:border-[#26262b] grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6 items-center">
                    <div>
                        <span class="block text-xs font-mono uppercase tracking-wider text-neutral-400 dark:text-neutral-500 mb-1">Client</span>
                        <span class="font-medium text-sm sm:text-base text-neutral-900 dark:text-white">{{ $project->client ?: 'Self-Initiated / Concept' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-mono uppercase tracking-wider text-neutral-400 dark:text-neutral-500 mb-1">Year</span>
                        <span class="font-mono text-sm sm:text-base text-neutral-900 dark:text-white">{{ $project->year ?: 'Ongoing' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-mono uppercase tracking-wider text-neutral-400 dark:text-neutral-500 mb-1">Discipline</span>
                        <span class="font-medium text-sm sm:text-base text-neutral-900 dark:text-white">{{ $project->category?->name ?? 'Design' }}</span>
                    </div>

                    <div class="col-span-2 sm:col-span-1 lg:col-span-1">
                        <span class="block text-xs font-mono uppercase tracking-wider text-neutral-400 dark:text-neutral-500 mb-1">Tools</span>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @forelse($project->tools_used ?? [] as $tool)
                                <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-neutral-200/80 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300">
                                    {{ $tool }}
                                </span>
                            @empty
                                <span class="text-xs text-neutral-400">Design Suite</span>
                            @endforelse
                        </div>
                    </div>

                    @if($project->project_url)
                        <div class="col-span-2 sm:col-span-4 lg:col-span-1 flex lg:justify-end">
                            <a
                                href="{{ $project->project_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-brand-400 text-ink hover:bg-brand-500 transition-all duration-200 shadow-xs focus-visible:outline-2 focus-visible:outline-brand-400"
                            >
                                <span>Live Project</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </header>

            <!-- Large Hero Cover Visual (Clickable for Lightbox) -->
            @if($coverUrl)
                <div class="mb-16 sm:mb-24">
                    <figure
                        class="relative rounded-3xl overflow-hidden bg-neutral-200 dark:bg-ink-soft shadow-2xl border border-black/10 dark:border-white/10 cursor-zoom-in group"
                        @click="openLightbox(0)"
                    >
                        <img
                            src="{{ $coverUrl }}"
                            @if($coverThumb && $coverLarge)
                                srcset="{{ $coverThumb }} 600w, {{ $coverLarge }} 1600w, {{ $coverOrig }} 2400w"
                                sizes="(max-width: 1280px) 100vw, 1280px"
                            @endif
                            alt="{{ $project->title }} — Cover Visual"
                            width="1600"
                            height="1000"
                            loading="eager"
                            fetchpriority="high"
                            class="w-full h-auto object-cover transition-transform duration-700 ease-out group-hover:scale-[1.01]"
                        />

                        <!-- Zoom cue overlay on hover -->
                        <div class="absolute bottom-6 right-6 px-4 py-2 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                            </svg>
                            <span>Click to expand image</span>
                        </div>
                    </figure>
                </div>
            @endif

            <!-- Project Narrative / Rich Text Story -->
            @if($project->description)
                <section class="mb-20 sm:mb-28 max-w-4xl mx-auto">
                    <div class="text-xs font-mono font-semibold uppercase tracking-widest text-brand-800 dark:text-brand-400 mb-4">
                        The Narrative & Case Study
                    </div>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300 leading-relaxed font-sans prose-headings:font-display prose-headings:font-bold prose-headings:tracking-tight prose-a:text-brand-800 dark:prose-a:text-brand-400 hover:prose-a:underline prose-img:rounded-2xl prose-img:shadow-lg">
                        {!! $project->description !!}
                    </div>
                </section>
            @endif

            <!-- Full-width Vertical Story Gallery -->
            @if($galleryMedia->count() > 0)
                <section class="mb-24 sm:mb-32 space-y-16 sm:space-y-24">
                    <div class="text-center max-w-2xl mx-auto mb-12">
                        <span class="text-xs font-mono font-semibold uppercase tracking-widest text-neutral-400 dark:text-neutral-500 block mb-2">
                            Visual Showcase
                        </span>
                        <h2 class="font-display font-bold text-2xl sm:text-4xl text-ink dark:text-paper">
                            Project Gallery & System Details
                        </h2>
                    </div>

                    @foreach($galleryMedia as $index => $media)
                        @php
                            $thumb = $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl();
                            $large = $media->hasGeneratedConversion('large') ? $media->getUrl('large') : $media->getUrl();
                            $orig = $media->getUrl();
                            $caption = $media->getCustomProperty('caption') ?: ($media->name ?: ($project->title . ' — Detail ' . ($index + 1)));
                            $lightboxIndex = $coverUrl ? ($index + 1) : $index;
                        @endphp

                        <figure class="group">
                            <div
                                class="relative rounded-3xl overflow-hidden bg-neutral-200 dark:bg-neutral-900 shadow-xl border border-[#e7e7e2] dark:border-[#26262b] cursor-zoom-in"
                                @click="openLightbox({{ $lightboxIndex }})"
                            >
                                <img
                                    src="{{ $large }}"
                                    srcset="{{ $thumb }} 600w, {{ $large }} 1600w, {{ $orig }} 2400w"
                                    sizes="(max-width: 1280px) 100vw, 1200px"
                                    alt="{{ $caption }}"
                                    width="1600"
                                    height="1000"
                                    loading="lazy"
                                    decoding="async"
                                    class="w-full h-auto object-cover transition-transform duration-700 ease-out group-hover:scale-[1.01]"
                                />

                                <div class="absolute bottom-6 right-6 px-4 py-2 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                                    </svg>
                                    <span>Expand image</span>
                                </div>
                            </div>

                            @if($caption)
                                <figcaption class="mt-4 text-center font-mono text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 tracking-wide max-w-2xl mx-auto">
                                    {{ $caption }}
                                </figcaption>
                            @endif
                        </figure>
                    @endforeach
                </section>
            @endif

            <!-- Alpine-Powered Lightbox Modal (Keyboard, Touch/Swipe, ESC to close, Captions) -->
            <div
                x-show="lightboxOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl flex flex-col justify-between select-none"
                role="dialog"
                aria-modal="true"
                aria-label="High-resolution image gallery lightbox"
                style="display: none;"
            >
                <!-- Lightbox Top Navigation Bar -->
                <div class="p-4 sm:p-6 flex items-center justify-between text-white border-b border-white/10 z-10">
                    <div class="flex items-center gap-4">
                        <span class="font-mono text-xs uppercase tracking-widest text-neutral-400">
                            {{ $project->title }}
                        </span>
                        <span class="text-neutral-600">|</span>
                        <span class="font-mono text-xs font-semibold px-2.5 py-1 rounded-full bg-white/10 text-neutral-200">
                            <span x-text="currentIndex + 1"></span> of <span x-text="items.length"></span>
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline-block text-xs font-mono text-neutral-400">
                            Use <kbd class="px-1.5 py-0.5 rounded bg-white/10 border border-white/20 text-neutral-300">←</kbd> <kbd class="px-1.5 py-0.5 rounded bg-white/10 border border-white/20 text-neutral-300">→</kbd> to navigate, <kbd class="px-1.5 py-0.5 rounded bg-white/10 border border-white/20 text-neutral-300">Esc</kbd> to close
                        </span>

                        <button
                            type="button"
                            @click="closeLightbox()"
                            class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-neutral-200 hover:text-brand-400 transition-colors focus-visible:outline-2 focus-visible:outline-brand-400"
                            aria-label="Close Lightbox"
                        >
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Lightbox Main Stage (Swipe, Touch, Viewport) -->
                <div
                    class="relative flex-grow flex items-center justify-center p-4 sm:p-8 overflow-hidden"
                    @touchstart="handleTouchStart($event)"
                    @touchend="handleTouchEnd($event)"
                    @click.self="closeLightbox()"
                >
                    <!-- Previous Image Button -->
                    <button
                        type="button"
                        x-show="items.length > 1"
                        @click.stop="prev()"
                        class="absolute left-4 sm:left-8 z-20 p-3 sm:p-4 rounded-full bg-black/70 hover:bg-black/95 text-white border border-white/20 hover:border-brand-400 hover:text-brand-400 transition-all transform hover:scale-110 focus-visible:outline-2 focus-visible:outline-brand-400 shadow-xl"
                        aria-label="Previous image"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Active Image Display -->
                    <div class="max-w-[92vw] max-h-[75vh] sm:max-h-[80vh] flex items-center justify-center">
                        <img
                            :src="items[currentIndex]?.src"
                            :alt="items[currentIndex]?.alt"
                            class="max-w-full max-h-[75vh] sm:max-h-[80vh] object-contain rounded-xl shadow-2xl transition-opacity duration-300"
                        />
                    </div>

                    <!-- Next Image Button -->
                    <button
                        type="button"
                        x-show="items.length > 1"
                        @click.stop="next()"
                        class="absolute right-4 sm:right-8 z-20 p-3 sm:p-4 rounded-full bg-black/70 hover:bg-black/95 text-white border border-white/20 hover:border-brand-400 hover:text-brand-400 transition-all transform hover:scale-110 focus-visible:outline-2 focus-visible:outline-brand-400 shadow-xl"
                        aria-label="Next image"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

                <!-- Lightbox Bottom Caption & Thumbnail Strip -->
                <div class="p-4 sm:p-6 bg-black/80 border-t border-white/10 flex flex-col items-center gap-3">
                    <p
                        x-text="items[currentIndex]?.caption"
                        class="text-xs sm:text-sm font-mono text-neutral-300 text-center tracking-wide line-clamp-1"
                    ></p>

                    <!-- Mini thumbnail selector -->
                    <div x-show="items.length > 1" class="flex items-center gap-2 overflow-x-auto max-w-full py-1 px-2 no-scrollbar">
                        <template x-for="(item, idx) in items" :key="idx">
                            <button
                                type="button"
                                @click="currentIndex = idx"
                                class="w-12 h-9 rounded-md overflow-hidden border-2 transition-all shrink-0"
                                :class="currentIndex === idx ? 'border-brand-400 scale-105 opacity-100 shadow-md ring-2 ring-brand-400/50' : 'border-transparent opacity-40 hover:opacity-80'"
                                :aria-label="'Jump to slide ' + (idx + 1)"
                            >
                                <img :src="item.thumb" :alt="item.alt" class="w-full h-full object-cover" />
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Next & Previous Project Navigation Footer -->
            <div class="pt-16 pb-20 border-t border-black/10 dark:border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($prevProject)
                        @php
                            $prevCover = $prevProject->getFirstMedia('cover');
                            $prevCoverUrl = $prevCover ? $prevCover->getUrl('thumb') : null;
                        @endphp
                        <a
                            href="{{ $prevProject->url }}"
                            class="group p-6 sm:p-8 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 hover:border-brand-400/50 hover:shadow-xl transition-all duration-300 flex items-center gap-6"
                        >
                            @if($prevCoverUrl)
                                <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 bg-neutral-200 dark:bg-neutral-800">
                                    <img src="{{ $prevCoverUrl }}" alt="{{ $prevProject->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                </div>
                            @endif
                            <div class="space-y-1 overflow-hidden">
                                <span class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                    </svg>
                                    <span>Previous Project</span>
                                </span>
                                <h3 class="font-display font-bold text-lg sm:text-xl text-ink dark:text-paper group-hover:text-brand-800 dark:group-hover:text-brand-400 transition-colors truncate">
                                    {{ $prevProject->title }}
                                </h3>
                                @if($prevProject->category)
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $prevProject->category->name }}</p>
                                @endif
                            </div>
                        </a>
                    @endif

                    @if($nextProject)
                        @php
                            $nextCover = $nextProject->getFirstMedia('cover');
                            $nextCoverUrl = $nextCover ? $nextCover->getUrl('thumb') : null;
                        @endphp
                        <a
                            href="{{ $nextProject->url }}"
                            class="group p-6 sm:p-8 rounded-3xl bg-paper-raised dark:bg-ink-soft border border-black/10 dark:border-white/10 hover:border-brand-400/50 hover:shadow-xl transition-all duration-300 flex items-center justify-between gap-6 md:text-right"
                        >
                            <div class="space-y-1 overflow-hidden order-2 md:order-1 flex-grow">
                                <span class="text-xs font-mono uppercase tracking-wider text-neutral-500 dark:text-neutral-400 flex items-center md:justify-end gap-1.5">
                                    <span>Next Project</span>
                                    <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                                <h3 class="font-display font-bold text-lg sm:text-xl text-ink dark:text-paper group-hover:text-brand-800 dark:group-hover:text-brand-400 transition-colors truncate">
                                    {{ $nextProject->title }}
                                </h3>
                                @if($nextProject->category)
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $nextProject->category->name }}</p>
                                @endif
                            </div>
                            @if($nextCoverUrl)
                                <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 bg-neutral-200 dark:bg-neutral-800 order-1 md:order-2">
                                    <img src="{{ $nextCoverUrl }}" alt="{{ $nextProject->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                </div>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Related Projects From Category -->
            @if($relatedProjects->count() > 0)
                <section class="py-16 sm:py-20 border-t border-black/10 dark:border-white/10">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
                        <x-section-heading
                            tag="More Work"
                            title="Related Projects"
                            description="Explore more curated work in {{ $project->category?->name ?? 'our catalogue' }}."
                            class="mb-0"
                        />

                        <a
                            href="{{ route('work.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-brand-800 dark:text-brand-400 hover:text-brand-900 dark:hover:text-brand-300 group"
                        >
                            <span>View all projects</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($relatedProjects as $related)
                            <x-project-card :project="$related" aspect="aspect-[4/3]" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function projectDetail(config) {
            return {
                lightboxOpen: config.lightboxOpen,
                currentIndex: config.currentIndex,
                touchStartX: config.touchStartX,
                touchEndX: config.touchEndX,
                items: config.items || [],

                openLightbox(index) {
                    if (!this.items.length) return;
                    this.currentIndex = Math.max(0, Math.min(index, this.items.length - 1));
                    this.lightboxOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                    document.body.classList.remove('overflow-hidden');
                },

                next() {
                    if (this.items.length <= 1) return;
                    this.currentIndex = (this.currentIndex + 1) % this.items.length;
                },

                prev() {
                    if (this.items.length <= 1) return;
                    this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
                },

                handleTouchStart(e) {
                    this.touchStartX = e.touches[0].clientX;
                },

                handleTouchEnd(e) {
                    this.touchEndX = e.changedTouches[0].clientX;
                    const diff = this.touchStartX - this.touchEndX;
                    if (Math.abs(diff) > 45) {
                        if (diff > 0) {
                            this.next();
                        } else {
                            this.prev();
                        }
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
