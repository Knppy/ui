<span
    data-slot="hover-card-trigger"
    x-ref="trigger"
    @mouseenter="_openCard()"
    @mouseleave="_closeCard()"
    @focus="_openCard()"
    @blur="_closeCard()"
    :aria-describedby="open ? $id('ui-hover-card') : undefined"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
