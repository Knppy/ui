@props([
    'align' => 'center',
    'side' => 'bottom',
    'sideOffset' => 4,
])

@php
    $placement = $side . ($align === 'center' ? '' : '-' . $align);
@endphp

<template x-teleport="body">
    <div
        x-show="open"
        x-cloak
        :id="$id('ui-hover-card')"
        {!! 'x-ui-anchor.' . $placement . '.offset.' . $sideOffset . '.no-min-width="$refs.trigger"' !!}
        @mouseenter="clearTimeout(_closeTimer)"
        @mouseleave="_closeCard()"
        @keydown.escape.window="open = false"
        data-slot="hover-card-content"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 w-64 origin-(--radix-hover-card-content-transform-origin) rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden') }}
    >
        {{ $slot }}
    </div>
</template>
