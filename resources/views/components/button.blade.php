@props([
    'variant' => 'primary', // 'primary', 'secondary', 'outline', 'ghost', 'white'
    'size' => 'md',       // 'sm', 'md', 'lg'
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'right', // 'left', 'right'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium font-sans rounded-full transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-400 disabled:opacity-50 disabled:pointer-events-none group cursor-pointer';

    $sizeClasses = [
        'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
        'md' => 'px-5 py-2.5 text-sm gap-2',
        'lg' => 'px-7 py-3.5 text-base gap-2.5 font-semibold',
    ][$size] ?? 'px-5 py-2.5 text-sm gap-2';

    $variantClasses = [
        'primary' => 'bg-brand-400 text-ink hover:bg-brand-500 font-semibold shadow-xs hover:shadow active:scale-[0.98]',
        'secondary' => 'border border-brand-500/80 text-brand-800 dark:text-brand-400 hover:bg-brand-400/10 font-medium active:scale-[0.98]',
        'outline' => 'border border-black/15 dark:border-white/15 text-ink dark:text-paper hover:border-brand-400 hover:text-brand-800 dark:hover:text-brand-400 active:scale-[0.98]',
        'ghost' => 'text-neutral-700 dark:text-neutral-300 hover:text-brand-800 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 active:scale-[0.98]',
        'white' => 'bg-paper-raised text-ink hover:bg-paper shadow-sm active:scale-[0.98]',
    ][$variant] ?? 'bg-brand-400 text-ink hover:bg-brand-500 font-semibold shadow-xs active:scale-[0.98]';

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
