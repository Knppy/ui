@props([
    'align' => 'center',
    'side' => 'bottom',
    'sideOffset' => 4,
])

@php
    $placement = $side.($align === 'center' ? '' : '-'.$align);
@endphp

<template x-teleport="body">
    <div
        x-show="open"
        x-cloak
        x-ui-dialog-layer
        :id="$id('ui-popover')"
        {!! 'x-ui-anchor.' . $placement . '.offset.' . $sideOffset . '="() => _anchor"' !!}
        @click.outside="!_anchor?.contains($event.target) && (open = false)"
        @keydown.escape.window="open = false"
        x-trap.noscroll.inert="open"
        x-ui-labelledby="{ label: '[data-slot=popover-title]', description: '[data-slot=popover-description]' }"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        data-slot="popover-content"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 w-72 origin-(--radix-popover-content-transform-origin) rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden') }}
    >
        {{ $slot }}
    </div>
</template>
