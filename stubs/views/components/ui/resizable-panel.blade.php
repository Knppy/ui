@props([
    'defaultSize' => null,
    'minSize' => 0,
    'maxSize' => 100,
])

<div
    data-slot="resizable-panel"
    @if ($defaultSize !== null) data-default-size="{{ $defaultSize }}" @endif
    data-min-size="{{ $minSize }}"
    data-max-size="{{ $maxSize }}"
    {{ $attributes->twMerge(['class' => 'min-h-0 min-w-0 shrink-0 overflow-auto']) }}
>
    {{ $slot }}
</div>
