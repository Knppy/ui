@props([
    'variant' => 'outline',
    'size' => 'icon',
])

<x-ui.button
    data-slot="carousel-next"
    :variant="$variant"
    :size="$size"
    x-bind:disabled="! canScrollNext"
    @click="scrollNext()"
    x-bind:class="orientation === 'horizontal'
        ? 'absolute top-1/2 -right-12 -translate-y-1/2'
        : 'absolute -bottom-12 left-1/2 -translate-x-1/2 rotate-90'"
    {{ $attributes->twMerge('size-8 rounded-full') }}
>
    <x-lucide-arrow-right />
    <span class="sr-only">Next slide</span>
</x-ui.button>
