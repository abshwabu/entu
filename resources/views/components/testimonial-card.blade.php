@props([
    'testimonial',
])

@php
    $avatar = $testimonial->getFirstMedia('avatar');
    $avatarUrl = $avatar ? $avatar->getUrl() : null;

    $initials = collect(explode(' ', $testimonial->author_name))
        ->map(fn ($segment) => mb_substr($segment, 0, 1))
        ->take(2)
        ->join('');
@endphp

<div
    {{ $attributes->merge(['class' => 'relative flex flex-col justify-between p-8 sm:p-10 rounded-3xl bg-neutral-100/90 dark:bg-[#151518] border border-[#e7e7e2] dark:border-[#26262b] transition-all duration-300 hover:border-amber-500/40 hover:shadow-lg']) }}
    x-data="{ shown: false }"
    x-intersect.margin.-80px="shown = true"
    :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-8': !shown }"
    class="transition-all duration-700 ease-out"
>
    <!-- Big Quotation Graphic -->
    <div class="mb-6 text-amber-600/30 dark:text-amber-500/20" aria-hidden="true">
        <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
        </svg>
    </div>

    <!-- Quote Body -->
    <blockquote class="text-base sm:text-lg text-neutral-800 dark:text-neutral-200 font-sans leading-relaxed mb-8 flex-grow">
        “{{ $testimonial->quote }}”
    </blockquote>

    <!-- Client Bio -->
    <div class="flex items-center gap-4 pt-6 border-t border-[#e2e2dc] dark:border-[#222226]">
        @if($avatarUrl)
            <img
                src="{{ $avatarUrl }}"
                alt="{{ $testimonial->author_name }}"
                loading="lazy"
                class="w-12 h-12 rounded-full object-cover ring-2 ring-amber-500/30"
            />
        @else
            <div class="w-12 h-12 rounded-full bg-amber-600/20 text-amber-700 dark:text-amber-400 font-display font-bold flex items-center justify-center text-sm ring-2 ring-amber-500/20">
                {{ $initials }}
            </div>
        @endif

        <div>
            <h4 class="font-display font-bold text-base text-neutral-900 dark:text-white">
                {{ $testimonial->author_name }}
            </h4>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 font-medium">
                {{ $testimonial->author_role }}
            </p>
        </div>
    </div>
</div>
