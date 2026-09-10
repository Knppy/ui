@props(['align' => 'center', 'side' => 'bottom', 'sideOffset' => 4])

@php($position = $side.($align === 'center' ? '' : '-'.$align))

<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition
        x-anchor.{{ $position }}.offset.{{ $sideOffset }}="trigger"
        x-on:mouseenter="cancelClose()"
        x-on:mouseleave="scheduleClose()"
        x-bind:data-state="open ? 'open' : 'closed'"
        data-side="{{ $side }}"
        data-align="{{ $align }}"
        data-slot="hover-card-content"
        {{ $attributes->twMerge(['class' => 'z-50 w-64 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden']) }}
    >
        {{ $slot }}
    </div>
</template>
