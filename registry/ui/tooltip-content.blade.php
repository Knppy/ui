@props([
    'side' => 'top',
    'sideOffset' => 0,
])

<template x-teleport="body">
    <div
        x-show="open"
        x-cloak
        :id="$id('ui-tooltip')"
        {!! 'x-ui-anchor.' . $side . '.offset.' . $sideOffset . '.no-min-width="$refs.trigger"' !!}
        @mouseenter="clearTimeout(_closeTimer)"
        @mouseleave="_closeTooltip()"
        @keydown.escape.window="open = false"
        role="tooltip"
        data-slot="tooltip-content"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('group z-50 w-fit origin-(--radix-tooltip-content-transform-origin) rounded-md bg-foreground px-3 py-1.5 text-xs text-balance text-background data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95') }}
    >
        {{ $slot }}
        <span
            aria-hidden="true"
            data-slot="tooltip-arrow"
            class="pointer-events-none absolute size-2.5 rotate-45 rounded-[2px] bg-foreground
                group-data-[side=top]:bottom-0 group-data-[side=top]:left-1/2 group-data-[side=top]:-translate-x-1/2 group-data-[side=top]:translate-y-[calc(50%_-_2px)]
                group-data-[side=bottom]:top-0 group-data-[side=bottom]:left-1/2 group-data-[side=bottom]:-translate-x-1/2 group-data-[side=bottom]:-translate-y-[calc(50%_-_2px)]
                group-data-[side=left]:right-0 group-data-[side=left]:top-1/2 group-data-[side=left]:-translate-y-1/2 group-data-[side=left]:translate-x-[calc(50%_-_2px)]
                group-data-[side=right]:left-0 group-data-[side=right]:top-1/2 group-data-[side=right]:-translate-y-1/2 group-data-[side=right]:-translate-x-[calc(50%_-_2px)]"
        ></span>
    </div>
</template>
