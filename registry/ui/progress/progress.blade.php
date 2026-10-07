@props([
    'value' => 0,
    'max' => 100,
])

@php
    $maximum = max(1, (float) $max);
    $current = min($maximum, max(0, (float) $value));
    $percentage = ($current / $maximum) * 100;
@endphp

<div
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="{{ $max }}"
    aria-valuenow="{{ $value }}"
    data-slot="progress"
    {{ $attributes->twMerge(['class' => 'relative h-2 w-full overflow-hidden rounded-full bg-primary/20']) }}
>
    <div
        data-slot="progress-indicator"
        class="bg-primary h-full w-full flex-1 transition-all"
        style="transform: translateX(-{{ 100 - $percentage }}%)"
    ></div>
</div>
