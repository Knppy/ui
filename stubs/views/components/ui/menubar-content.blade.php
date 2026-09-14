@props(['align' => 'start', 'sideOffset' => 8])

@php($position = 'bottom'.($align === 'center' ? '' : '-'.$align))

<template x-teleport="body">
    <div
        x-cloak
        x-show="isOpen(menu)"
        x-transition
        x-init="registerContent(menu, $el)"
        x-anchor.{{ $position }}.offset.{{ $sideOffset }}="triggerFor(menuValue($el))"
        x-on:click.outside="closeMenu(false)"
        x-on:keydown="handleContentKeydown($event)"
        x-bind:data-state="isOpen(menu) ? 'open' : 'closed'"
        role="menu"
        tabindex="-1"
        data-slot="menubar-content"
        data-align="{{ $align }}"
        {{ $attributes->twMerge(['class' => 'z-50 min-w-48 overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-hidden']) }}
    >
        {{ $slot }}
    </div>
</template>
