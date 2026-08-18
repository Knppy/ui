<div
    data-slot="carousel-content"
    x-ref="viewport"
    class="overflow-hidden"
>
    <div
        data-slot="carousel-content-inner"
        x-bind:class="orientation === 'horizontal' ? '-ml-4 flex' : '-mt-4 flex flex-col'"
        {{ $attributes->twMerge('flex') }}
    >
        {{ $slot }}
    </div>
</div>
