<span
    data-slot="tooltip-trigger"
    x-ref="trigger"
    @mouseenter="_openTooltip()"
    @mouseleave="_closeTooltip()"
    @focus="_openTooltip()"
    @blur="_closeTooltip()"
    @keydown.space.prevent="open = false"
    @keydown.enter.prevent="open = false"
    :aria-describedby="open ? $id('ui-tooltip') : undefined"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
