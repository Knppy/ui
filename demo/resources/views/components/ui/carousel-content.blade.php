<div
    x-init="initialize($el)"
    x-on:scroll.passive="update()"
    data-slot="carousel-content"
    {{ $attributes->twMerge(['class' => 'flex snap-mandatory overflow-auto scroll-smooth [scrollbar-width:none] [&::-webkit-scrollbar]:hidden group-data-[orientation=horizontal]/carousel:-ml-4 group-data-[orientation=horizontal]/carousel:snap-x group-data-[orientation=vertical]/carousel:-mt-4 group-data-[orientation=vertical]/carousel:max-h-full group-data-[orientation=vertical]/carousel:flex-col group-data-[orientation=vertical]/carousel:snap-y']) }}
>
    {{ $slot }}
</div>
