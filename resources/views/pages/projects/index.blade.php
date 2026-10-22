<x-app-layout
    :title="$category ? ($category->name . ' Portfolio') : 'Selected Work & Portfolio'"
    :description="setting('tagline', 'Browse through curated branding, packaging, and design projects by Entu Creative.')"
>
    <div
        x-data="portfolioGallery({
            activeSlug: '{{ $category?->slug ?? '' }}',
            currentUrl: '{{ url()->current() }}',
            allUrl: '{{ route('work.index') }}',
            nextPageUrl: '{{ $projects->nextPageUrl() }}',
            hasMore: {{ $projects->hasMorePages() ? 'true' : 'false' }},
            totalProjects: {{ $projects->total() }}
        })"
        @popstate.window="handlePopState($event)"
        class="py-12 sm:py-20"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-12 sm:mb-16 text-center max-w-3xl mx-auto">
                <span class="inline-block text-xs font-mono font-semibold uppercase tracking-widest text-ink dark:text-mint-400 mb-3">
                    Portfolio Gallery
                </span>
                <h1 class="font-display font-extrabold text-4xl sm:text-6xl text-ink dark:text-paper tracking-tight mb-4">
                    Selected Work
                </h1>
                <p class="text-neutral-600 dark:text-neutral-400 text-base sm:text-lg leading-relaxed">
                    A comprehensive catalogue of visual identities, editorial publications, custom packaging systems, and digital experiments.
                </p>
            </div>

            <!-- Category Filter Pills -->
            <div class="mb-12 sm:mb-16">
                <nav
                    class="flex items-center justify-start sm:justify-center gap-2 overflow-x-auto pb-4 pt-1 px-1 no-scrollbar scroll-smooth"
                    aria-label="Category Filters"
                >
                    <!-- "All Work" Pill -->
                    <a
                        href="{{ route('work.index') }}"
                        @click.prevent="filter('', '{{ route('work.index') }}')"
                        class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs tracking-wider transition-all duration-200 border focus-visible:outline-2 focus-visible:outline-mint-500 cursor-pointer"
                        :class="activeSlug === '' 
                            ? 'bg-mint-500 text-panel-deep border-mint-500 font-bold shadow-xs' 
                            : 'bg-paper-raised dark:bg-panel-soft text-neutral-700 dark:text-neutral-300 border-black/10 dark:border-white/10 hover:border-mint-500/50'"
                    >
                        <span>All Projects</span>
                        <span
                            class="px-2 py-0.5 rounded-full text-[11px] font-mono transition-colors"
                            :class="activeSlug === '' 
                                ? 'bg-panel-deep/15 text-panel-deep font-bold' 
                                : 'bg-black/5 dark:bg-white/5 text-neutral-600 dark:text-neutral-400'"
                        >
                            {{ $allCount }}
                        </span>
                    </a>

                    <!-- Specific Category Pills -->
                    @foreach($categories as $cat)
                        <a
                            href="{{ route('work.category', $cat->slug) }}"
                            @click.prevent="filter('{{ $cat->slug }}', '{{ route('work.category', $cat->slug) }}')"
                            class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs tracking-wider transition-all duration-200 border focus-visible:outline-2 focus-visible:outline-mint-500 cursor-pointer"
                            :class="activeSlug === '{{ $cat->slug }}' 
                                ? 'bg-mint-500 text-panel-deep border-mint-500 font-bold shadow-xs' 
                                : 'bg-paper-raised dark:bg-panel-soft text-neutral-700 dark:text-neutral-300 border-black/10 dark:border-white/10 hover:border-mint-500/50'"
                        >
                            <span>{{ $cat->name }}</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-mono transition-colors"
                                :class="activeSlug === '{{ $cat->slug }}' 
                                    ? 'bg-panel-deep/15 text-panel-deep font-bold' 
                                    : 'bg-black/5 dark:bg-white/5 text-neutral-600 dark:text-neutral-400'"
                            >
                                {{ $cat->projects_count }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Masonry Grid Container with Alpine Loading Overlay -->
            <div class="relative min-h-[400px]">
                <!-- Loading State Indicator -->
                <div
                    x-show="isLoading"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 z-20 bg-paper/60 dark:bg-panel/60 backdrop-blur-xs flex items-center justify-center rounded-3xl"
                    style="display: none;"
                >
                    <div class="p-4 rounded-2xl bg-paper-raised dark:bg-panel-soft shadow-xl border border-black/10 dark:border-white/10 flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-mint-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span class="text-xs font-mono uppercase tracking-wider text-ink dark:text-paper font-semibold">Updating Projects...</span>
                    </div>
                </div>

                <!-- Projects Masonry Columns -->
                <div
                    id="projects-grid"
                    class="columns-1 md:columns-2 lg:columns-3 gap-8 [column-fill:_balance] transition-opacity duration-300"
                    :class="{ 'opacity-50 pointer-events-none': isLoading }"
                >
                    @include('pages.projects._grid_items', ['projects' => $projects])
                </div>
            </div>

            <!-- "Load More" Pagination Control (Interactive when JS is active) -->
            <div x-show="hasMore" class="mt-16 text-center" style="display: {{ $projects->hasMorePages() ? 'block' : 'none' }};">
                <button
                    type="button"
                    @click="loadMore()"
                    :disabled="isLoadingMore"
                    class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full text-sm font-bold tracking-wide bg-mint-500 text-panel-deep hover:bg-mint-400 transition-all duration-300 shadow-md hover:shadow-xl disabled:opacity-60 disabled:cursor-not-allowed group focus-visible:outline-2 focus-visible:outline-mint-500 cursor-pointer"
                >
                    <span x-show="!isLoadingMore">Load More Projects</span>
                    <span x-show="isLoadingMore" style="display: none;">Loading More...</span>

                    <svg
                        x-show="!isLoadingMore"
                        class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>

                    <svg
                        x-show="isLoadingMore"
                        class="animate-spin h-4 w-4 text-current"
                        fill="none"
                        viewBox="0 0 24 24"
                        style="display: none;"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>
            </div>

            <!-- Standard Fallback Pagination for Non-JS Environments -->
            <noscript>
                <div class="mt-16 flex justify-center">
                    {{ $projects->links() }}
                </div>
            </noscript>
        </div>
    </div>

    @push('scripts')
    <script>
        function portfolioGallery(config) {
            return {
                activeSlug: config.activeSlug,
                currentUrl: config.currentUrl,
                allUrl: config.allUrl,
                nextPageUrl: config.nextPageUrl,
                hasMore: config.hasMore,
                totalProjects: config.totalProjects,
                isLoading: false,
                isLoadingMore: false,

                async filter(slug, url) {
                    if (this.activeSlug === slug && !this.isLoading) return;

                    this.isLoading = true;
                    this.activeSlug = slug;

                    try {
                        window.history.pushState({ slug: slug, url: url }, '', url);

                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-Alpine-Request': '1'
                            }
                        });

                        if (!response.ok) {
                            window.location.href = url;
                            return;
                        }

                        const data = await response.json();
                        const grid = document.getElementById('projects-grid');
                        if (grid) {
                            grid.innerHTML = data.html;
                        }

                        this.nextPageUrl = data.nextPageUrl;
                        this.hasMore = data.hasMorePages;
                        this.totalProjects = data.total;

                        if (data.pageTitle) {
                            document.title = data.pageTitle;
                        }

                        // Smoothly scroll back to top of grid on mobile if needed
                        const navEl = document.querySelector('[aria-label="Category Filters"]');
                        if (navEl && window.scrollY > navEl.offsetTop) {
                            navEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    } catch (err) {
                        console.error('Filtering failed, navigating normally:', err);
                        window.location.href = url;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async loadMore() {
                    if (!this.nextPageUrl || this.isLoadingMore) return;

                    this.isLoadingMore = true;

                    try {
                        const response = await fetch(this.nextPageUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-Alpine-Request': '1'
                            }
                        });

                        if (!response.ok) throw new Error('Could not load next page');

                        const data = await response.json();
                        const grid = document.getElementById('projects-grid');
                        if (grid) {
                            grid.insertAdjacentHTML('beforeend', data.html);
                        }

                        this.nextPageUrl = data.nextPageUrl;
                        this.hasMore = data.hasMorePages;
                    } catch (err) {
                        console.error('Load more failed:', err);
                    } finally {
                        this.isLoadingMore = false;
                    }
                },

                handlePopState(event) {
                    const slug = event.state?.slug ?? '';
                    const url = event.state?.url ?? window.location.href;
                    this.filter(slug, url);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
