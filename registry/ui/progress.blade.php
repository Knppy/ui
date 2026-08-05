@props([
    'value' => 0,
    'max' => 100,
])

@php
    $percentage = $max > 0 ? min(100, ($value / $max) * 100) : 0;
    $transform = 100 - $percentage;
@endphp

<div
    data-slot="progress"
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="{{ $max }}"
    aria-valuenow="{{ $value }}"
    {{ $attributes->twMerge('bg-primary/20 relative h-2 w-full overflow-hidden rounded-full') }}
>
    <div
        data-slot="progress-indicator"
        class="bg-primary h-full w-full flex-1 transition-all"
        style="transform: translateX(-{{ $transform }}%);"
    ></div>
</div>
