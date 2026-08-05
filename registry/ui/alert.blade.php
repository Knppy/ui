@props([
    'variant' => 'default',
])

@php
    $base = 'relative grid w-full grid-cols-[0_1fr] items-start gap-y-0.5 rounded-lg border px-4 py-3 text-sm has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] has-[>svg]:gap-x-3 [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current';

    $variants = [
        'variant' => [
            'default' => 'bg-card text-card-foreground',
            'destructive' => 'bg-card text-destructive *:data-[slot=alert-description]:text-destructive/90 [&>svg]:text-current',
        ],
    ];

    $classes = [
        $base,
        $variants['variant'][$variant] ?? $variants['variant']['default'],
    ];
@endphp

<div data-slot="alert" role="alert" {{ $attributes->twMerge($classes) }}>{{ $slot }}</div>
