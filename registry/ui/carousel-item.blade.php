<div
    data-slot="carousel-item"
    role="group"
    aria-roledescription="slide"
    x-bind:class="orientation === 'horizontal' ? 'pl-4' : 'pt-4'"
    {{ $attributes->twMerge('min-w-0 shrink-0 grow-0 basis-full') }}
>
    {{ $slot }}
</div>
