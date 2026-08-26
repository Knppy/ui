<span
    data-slot="popover-trigger"
    x-ref="trigger"
    x-init="_anchor ??= $el"
    @click.prevent="open = !open"
    x-ui-trigger="{ haspopup: 'dialog', controls: $id('ui-popover') }"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
