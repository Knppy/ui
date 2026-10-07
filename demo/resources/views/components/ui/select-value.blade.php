@props(['placeholder' => null])

<span
    x-text="selectedLabel || @js($placeholder)"
    x-bind:data-placeholder="selectedLabel ? null : ''"
    data-slot="select-value"
    {{ $attributes }}
>{{ $slot }}</span>
