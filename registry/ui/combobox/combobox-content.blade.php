@props(['side' => 'bottom', 'sideOffset' => 6, 'align' => 'start', 'alignOffset' => 0])

<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition
        x-init="registerContent($el, @js($side), @js($align), @js($sideOffset), @js($alignOffset))"
        x-on:click.outside="if (! (anchor || input).contains($event.target)) closeList();"
        x-bind:id="contentId"
        x-bind:data-state="open ? 'open' : 'closed'"
        x-bind:data-empty="visibleOptions.length ? null : ''"
        data-slot="combobox-content"
        data-side="{{ $side }}"
        data-align="{{ $align }}"
        {{ $attributes->twMerge(['class' => 'group/combobox-content fixed z-50 max-h-96 overflow-hidden rounded-md bg-popover text-popover-foreground shadow-md ring-1 ring-foreground/10']) }}
    >
        {{ $slot }}
    </div>
</template>
