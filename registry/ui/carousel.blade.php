@props([
    'orientation' => 'horizontal',
    'opts' => [],
])

<div
    data-slot="carousel"
    x-data="uiCarousel({ orientation: @js($orientation), opts: @js($opts) })"
    @keydown.arrow-left.prevent="orientation === 'horizontal' && scrollPrev()"
    @keydown.arrow-right.prevent="orientation === 'horizontal' && scrollNext()"
    @keydown.arrow-up.prevent="orientation === 'vertical' && scrollPrev()"
    @keydown.arrow-down.prevent="orientation === 'vertical' && scrollNext()"
    role="region"
    aria-roledescription="carousel"
    {{ $attributes->twMerge('relative') }}
>
    {{ $slot }}
</div>
