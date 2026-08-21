@props([
    'variant' => 'outline',
    'size' => 'icon',
])

<x-ui.button
    data-slot="carousel-previous"
    :variant="$variant"
    :size="$size"
    x-bind:disabled="! canScrollPrev"
    @click="scrollPrev()"
    x-bind:class="
        orientation === 'horizontal'
            ? 'absolute top-1/2 -left-12 -translate-y-1/2'
            : 'absolute -top-12 left-1/2 -translate-x-1/2 rotate-90'
    "
    {{ $attributes->twMerge('size-8 rounded-full') }}
>
    <x-lucide-arrow-left />
    <span class="sr-only">Previous slide</span>
</x-ui.button>
