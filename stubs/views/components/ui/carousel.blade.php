@props(['orientation' => 'horizontal'])

<div
    x-data="uiCarousel(@js($orientation))"
    x-on:keydown.capture="handleKeydown($event)"
    role="region"
    aria-roledescription="carousel"
    data-slot="carousel"
    data-orientation="{{ $orientation }}"
    {{ $attributes->twMerge(['class' => 'group/carousel relative']) }}
>
    {{ $slot }}
</div>
