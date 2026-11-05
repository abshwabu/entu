@props([
    'variant' => 'icon', // 'icon' or 'full'
    'size' => 'w-9 h-9',
    'alt' => 'Entu Creative',
])

@php
    $isIcon = $variant === 'icon';
    $svgIconExists = file_exists(public_path('brand/logo-icon.svg'));
    $svgLogoExists = file_exists(public_path('brand/logo.svg'));

    $src = $isIcon
        ? ($svgIconExists ? asset('brand/logo-icon.svg') : asset('brand/logo-icon.png'))
        : ($svgLogoExists ? asset('brand/logo.svg') : asset('brand/logo.png'));
@endphp

<div {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center rounded-full overflow-hidden p-0.5 bg-ink text-white shadow-xs {$size}"]) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover rounded-full" />
</div>
