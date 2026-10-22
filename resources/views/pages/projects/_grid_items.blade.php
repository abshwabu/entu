@forelse($projects as $index => $project)
    <div class="break-inside-avoid inline-block w-full mb-8 transform transition-all duration-500">
        @php
            // Vary aspect ratios for an authentic editorial masonry rhythm
            $aspectRatio = match($index % 4) {
                0 => 'aspect-[4/3]',
                1 => 'aspect-[3/4]',
                2 => 'aspect-[1/1]',
                default => 'aspect-[16/10]',
            };
        @endphp
        <x-project-card :project="$project" :aspect="$aspectRatio" />
    </div>
@empty
    <div class="col-span-full w-full py-20 text-center break-inside-avoid">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center text-neutral-400">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
        </div>
        <h3 class="font-display font-semibold text-lg text-neutral-900 dark:text-white mb-2">No projects found</h3>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 max-w-sm mx-auto mb-6">
            There are currently no published projects in this category.
        </p>
        <a
            href="{{ route('work.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider bg-mint-500 text-panel-deep hover:bg-mint-400 transition-colors shadow-sm"
        >
            View All Work
        </a>
    </div>
@endforelse
