@props([
    'variant' => 'outline',
    'size' => 'icon',
])

<x-ui.button
    type="button"
    dataSlot="carousel-previous"
    :variant="$variant"
    :size="$size"
    x-on:click="scrollPrevious()"
    x-bind:disabled="! canScrollPrevious"
    {{ $attributes->twMerge(['class' => 'absolute size-8 rounded-full group-data-[orientation=horizontal]/carousel:top-1/2 group-data-[orientation=horizontal]/carousel:-left-12 group-data-[orientation=horizontal]/carousel:-translate-y-1/2 group-data-[orientation=vertical]/carousel:-top-12 group-data-[orientation=vertical]/carousel:left-1/2 group-data-[orientation=vertical]/carousel:-translate-x-1/2 group-data-[orientation=vertical]/carousel:rotate-90']) }}
>
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7M19 12H5" /></svg>
    <span class="sr-only">Previous slide</span>
</x-ui.button>
