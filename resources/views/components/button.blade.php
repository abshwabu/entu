@props([
    'variant' => 'primary', // 'primary', 'secondary', 'outline', 'ghost', 'white'
    'size' => 'md',       // 'sm', 'md', 'lg'
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'right', // 'left', 'right'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium font-sans rounded-full transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500 disabled:opacity-50 disabled:pointer-events-none group cursor-pointer';

    $sizeClasses = [
        'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
        'md' => 'px-5 py-2.5 text-sm gap-2',
        'lg' => 'px-7 py-3.5 text-base gap-2.5 font-semibold',
    ][$size] ?? 'px-5 py-2.5 text-sm gap-2';

    $variantClasses = [
        'primary' => 'bg-amber-600 text-white hover:bg-amber-700 shadow-sm hover:shadow active:scale-[0.98]',
        'secondary' => 'bg-neutral-900 text-white hover:bg-neutral-800 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-white shadow-sm active:scale-[0.98]',
        'outline' => 'border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-amber-600 dark:hover:border-amber-500 hover:text-amber-600 dark:hover:text-amber-400 active:scale-[0.98]',
        'ghost' => 'text-neutral-700 dark:text-neutral-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-neutral-100 dark:hover:bg-neutral-800/60 active:scale-[0.98]',
        'white' => 'bg-white text-neutral-900 hover:bg-neutral-100 shadow-sm active:scale-[0.98]',
    ][$variant] ?? 'bg-amber-600 text-white hover:bg-amber-700 shadow-sm active:scale-[0.98]';

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="shrink-0 transition-transform group-hover:-translate-x-0.5">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($icon && $iconPosition === 'right')
            <span class="shrink-0 transition-transform group-hover:translate-x-0.5">{{ $icon }}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="shrink-0 transition-transform group-hover:-translate-x-0.5">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($icon && $iconPosition === 'right')
            <span class="shrink-0 transition-transform group-hover:translate-x-0.5">{{ $icon }}</span>
        @endif
    </button>
@endif
