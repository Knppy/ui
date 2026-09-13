@props(['align' => 'start', 'side' => 'bottom', 'sideOffset' => 4])

@php($position = $side.($align === 'center' ? '' : '-'.$align))

<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition
        x-init="registerContent($el)"
        x-anchor.{{ $position }}.offset.{{ $sideOffset }}="trigger"
        x-on:click.outside="closeMenu(false)"
        x-on:keydown="handleKeydown($event)"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="menu"
        tabindex="-1"
        data-slot="dropdown-menu-content"
        data-side="{{ $side }}"
        data-align="{{ $align }}"
        {{ $attributes->twMerge(['class' => 'z-50 max-h-96 min-w-32 overflow-x-hidden overflow-y-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-hidden']) }}
    >
        {{ $slot }}
    </div>
</template>
