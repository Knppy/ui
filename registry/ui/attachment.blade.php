@props([
    'state' => 'done',
    'size' => 'default',
    'orientation' => 'horizontal',
])

@php
    $base = 'group/attachment relative flex w-fit max-w-full min-w-0 shrink-0 flex-wrap rounded-xl border bg-card text-card-foreground transition-colors focus-within:ring-1 focus-within:ring-ring/50 has-[>a,>button]:hover:bg-muted/50 data-[state=error]:border-destructive/30 data-[state=idle]:border-dashed';

    $sizes = [
        'default' => 'gap-2 text-sm has-data-[slot=attachment-content]:px-2.5 has-data-[slot=attachment-content]:py-2 has-data-[slot=attachment-media]:p-2',
        'sm' => 'gap-2.5 text-xs has-data-[slot=attachment-content]:px-2 has-data-[slot=attachment-content]:py-1.5 has-data-[slot=attachment-media]:p-1.5',
        'xs' => 'gap-1.5 rounded-lg text-xs has-data-[slot=attachment-content]:px-1.5 has-data-[slot=attachment-content]:py-1 has-data-[slot=attachment-media]:p-1',
    ];

    $orientations = [
        'horizontal' => 'min-w-40 items-center',
        'vertical' => 'w-24 flex-col has-data-[slot=attachment-content]:w-30',
    ];

    $classes = [
        $base,
        $sizes[$size] ?? $sizes['default'],
        $orientations[$orientation] ?? $orientations['horizontal'],
    ];
@endphp

<div
    data-slot="attachment"
    data-state="{{ $state }}"
    data-size="{{ $size }}"
    data-orientation="{{ $orientation }}"
    {{ $attributes->twMerge($classes) }}
>
    {{ $slot }}
</div>
