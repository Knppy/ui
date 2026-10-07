@props([
    'variant' => 'outline',
    'size' => 'icon',
])

<x-ui.button
    type="button"
    dataSlot="carousel-next"
    :variant="$variant"
    :size="$size"
    x-on:click="scrollNext()"
    x-bind:disabled="! canScrollNext"
    {{ $attributes->twMerge(['class' => 'absolute size-8 rounded-full group-data-[orientation=horizontal]/carousel:top-1/2 group-data-[orientation=horizontal]/carousel:-right-12 group-data-[orientation=horizontal]/carousel:-translate-y-1/2 group-data-[orientation=vertical]/carousel:-bottom-12 group-data-[orientation=vertical]/carousel:left-1/2 group-data-[orientation=vertical]/carousel:-translate-x-1/2 group-data-[orientation=vertical]/carousel:rotate-90']) }}
>
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 5 7 7-7 7M5 12h14" /></svg>
    <span class="sr-only">Next slide</span>
</x-ui.button>
