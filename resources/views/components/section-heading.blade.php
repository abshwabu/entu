@props([
    'tag' => null,
    'title',
    'description' => null,
    'align' => 'left', // 'left' or 'center'
])

@php
    $alignmentClasses = [
        'left' => 'text-left items-start',
        'center' => 'text-center items-center mx-auto',
    ][$align] ?? 'text-left items-start';
@endphp

<div
    {{ $attributes->merge(['class' => "flex flex-col {$alignmentClasses} mb-12 sm:mb-16 max-w-3xl"]) }}
    x-data="{ shown: false }"
    x-intersect.margin.-100px="shown = true"
    :class="{ 'opacity-100 translate-y-0': shown, 'opacity-0 translate-y-6': !shown }"
    class="transition-all duration-700 ease-out"
>
    @if($tag)
        <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest font-semibold text-ink dark:text-mint-400 mb-3 px-3 py-1 rounded-full bg-mint-500/15 border border-mint-500/30">
            <span class="w-1.5 h-1.5 rounded-full bg-mint-600 dark:bg-mint-400"></span>
            {{ $tag }}
        </span>
    @endif

    <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-ink dark:text-paper leading-[1.15]">
        {{ $title }}
    </h2>

    @if($description)
        <p class="mt-4 text-base sm:text-lg text-neutral-600 dark:text-neutral-400 font-normal leading-relaxed">
            {{ $description }}
        </p>
    @endif
</div>
