@props([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
])

@php
    $base = 'group/item flex flex-wrap items-center rounded-md border border-transparent text-sm transition-colors duration-100 outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50';

    $variants = [
        'variant' => [
            'default' => 'bg-transparent',
            'outline' => 'border-border',
            'muted' => 'bg-muted/50',
        ],
        'size' => [
            'default' => 'gap-4 p-4',
            'sm' => 'gap-2.5 px-4 py-3',
        ],
    ];

    $classes = [
        $base,
        $variants['variant'][$variant] ?? $variants['variant']['default'],
        $variants['size'][$size] ?? $variants['size']['default'],
    ];
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        data-slot="item"
        data-variant="{{ $variant }}"
        data-size="{{ $size }}"
        {{ $attributes->twMerge($classes) }}
    >
        {{ $slot }}
    </a>
@else
    <div data-slot="item" data-variant="{{ $variant }}" data-size="{{ $size }}" {{ $attributes->twMerge($classes) }}>
        {{ $slot }}
    </div>
@endif
