@props([
    'side' => 'bottom',
    'align' => 'end',
])

@php
    $base = 'absolute z-10 flex w-fit shrink-0 items-center justify-center gap-1 rounded-full bg-muted px-1.5 py-0.5 text-sm ring-3 ring-card has-[button]:p-0';

    $sides = [
        'top'    => 'top-0 -translate-y-3/4',
        'bottom' => 'bottom-0 translate-y-3/4',
    ];

    $aligns = [
        'start' => 'left-3',
        'end'   => 'right-3',
    ];

    $classes = [
        $base,
        $sides[$side] ?? $sides['bottom'],
        $aligns[$align] ?? $aligns['end'],
    ];
@endphp

<div
    data-slot="bubble-reactions"
    data-align="{{ $align }}"
    data-side="{{ $side }}"
    {{ $attributes->twMerge($classes) }}
>
    {{ $slot }}
</div>
