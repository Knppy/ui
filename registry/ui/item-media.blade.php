@props([
    'variant' => 'default',
])

@php
    $base = 'flex shrink-0 items-center justify-center gap-2 group-has-[[data-slot=item-description]]/item:translate-y-0.5 group-has-[[data-slot=item-description]]/item:self-start [&_svg]:pointer-events-none';

    $variants = [
        'variant' => [
            'default' => 'bg-transparent',
            'icon' => "size-8 rounded-sm border bg-muted [&_svg:not([class*='size-'])]:size-4",
            'image' => 'size-10 overflow-hidden rounded-sm [&_img]:size-full [&_img]:object-cover',
        ],
    ];

    $classes = [
        $base,
        $variants['variant'][$variant] ?? $variants['variant']['default'],
    ];
@endphp

<div data-slot="item-media" data-variant="{{ $variant }}" {{ $attributes->twMerge($classes) }}>{{ $slot }}</div>
