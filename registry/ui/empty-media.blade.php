@props([
    'variant' => 'default',
])

@php
    $base = 'mb-2 flex shrink-0 items-center justify-center [&_svg]:pointer-events-none [&_svg]:shrink-0';

    $variants = [
        'variant' => [
            'default' => 'bg-transparent',
            'icon' => 'flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground [&_svg:not([class*=\'size-\'])]:size-6',
        ],
    ];

    $classes = [
        $base,
        $variants['variant'][$variant] ?? $variants['variant']['default'],
    ];
@endphp

<div data-slot="empty-icon" data-variant="{{ $variant }}" {{ $attributes->twMerge($classes) }}>{{ $slot }}</div>
