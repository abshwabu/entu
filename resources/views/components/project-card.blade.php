@props([
    'project',
    'aspect' => 'aspect-[4/3]',
])

@php
    $coverMedia = $project->getFirstMedia('cover');
    $coverThumb = $coverMedia?->hasGeneratedConversion('thumb') ? $coverMedia->getUrl('thumb') : $coverMedia?->getUrl();
    $coverLarge = $coverMedia?->hasGeneratedConversion('large') ? $coverMedia->getUrl('large') : $coverMedia?->getUrl();
    $coverUrl = $coverLarge ?: ($coverMedia ? $coverMedia->getUrl() : null);
    $categoryName = $project->category?->name;
    $detailUrl = $project->url;
@endphp

<article
    {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-2xl bg-neutral-100 dark:bg-[#141417] border border-[#e7e7e2] dark:border-[#26262b] transition-all duration-300 hover:shadow-xl hover:border-amber-500/50 break-inside-avoid w-full']) }}
    x-data="{ shown: false }"
    x-intersect.margin.-80px="shown = true"
    :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-8': !shown }"
    class="transition-all duration-700 ease-out"
>
    <!-- Visual Image Wrapper -->
    <div class="relative w-full {{ $aspect }} overflow-hidden bg-neutral-200 dark:bg-neutral-800">
        @if($coverUrl)
            <img
                src="{{ $coverUrl }}"
                @if($coverThumb && $coverLarge)
                    srcset="{{ $coverThumb }} 600w, {{ $coverLarge }} 1600w"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 500px"
                @endif
                alt="{{ $project->title }} — {{ $categoryName ?? 'Design Project' }}"
                width="600"
                height="450"
                loading="lazy"
                decoding="async"
                class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105"
            />
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-600/20 to-neutral-900/40 text-neutral-400">
                <span class="font-display font-medium text-lg tracking-wider">ENTU CREATIVE</span>
            </div>
        @endif

        <!-- Subtle Gradient Overlay on Hover -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 text-white w-full flex items-center justify-between">
                <div class="text-xs space-y-0.5">
                    @if($project->client)
                        <p class="text-neutral-300 font-medium uppercase tracking-wider">Client: {{ $project->client }}</p>
                    @endif
                    @if($project->year)
                        <p class="text-neutral-400 font-mono">{{ $project->year }}</p>
                    @endif
                </div>

                <span class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white group-hover:bg-amber-600 transition-colors">
                    <svg class="w-5 h-5 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </span>
            </div>
        </div>

        @if($categoryName && $project->category)
            <div class="absolute top-4 left-4 z-10">
                <a
                    href="{{ route('work.category', $project->category->slug) }}"
                    class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-black/60 backdrop-blur-md text-white border border-white/10 hover:bg-amber-600 transition-colors"
                >
                    {{ $categoryName }}
                </a>
            </div>
        @elseif($categoryName)
            <div class="absolute top-4 left-4 z-10">
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-black/60 backdrop-blur-md text-white border border-white/10">
                    {{ $categoryName }}
                </span>
            </div>
        @endif

        @if($project->is_featured)
            <div class="absolute top-4 right-4 z-10">
                <span class="w-7 h-7 rounded-full bg-amber-500/90 text-white flex items-center justify-center shadow-xs" title="Featured Project">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </span>
            </div>
        @endif
    </div>

    <!-- Content Meta -->
    <div class="p-6 flex flex-col flex-grow justify-between gap-3">
        <div>
            <h3 class="font-display font-bold text-xl sm:text-2xl text-neutral-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                <a href="{{ $detailUrl }}" class="focus:outline-none">
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    {{ $project->title }}
                </a>
            </h3>

            @if($project->summary)
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 leading-relaxed">
                    {{ $project->summary }}
                </p>
            @endif
        </div>

        @if(!empty($project->tools_used))
            <div class="pt-3 border-t border-[#ededec] dark:border-[#222226] flex flex-wrap gap-1.5 z-10">
                @foreach(array_slice($project->tools_used, 0, 3) as $tool)
                    <span class="text-[11px] font-mono px-2 py-0.5 rounded-md bg-neutral-200/70 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300">
                        {{ $tool }}
                    </span>
                @endforeach
                @if(count($project->tools_used) > 3)
                    <span class="text-[11px] font-mono px-1.5 py-0.5 text-neutral-500">
                        +{{ count($project->tools_used) - 3 }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</article>
