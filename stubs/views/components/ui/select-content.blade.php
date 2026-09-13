@props(['align' => 'center', 'side' => 'bottom', 'sideOffset' => 4, 'position' => 'item-aligned'])

<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition
        x-init="registerContent($el, @js($position), @js($side), @js($align), @js($sideOffset))"
        x-on:click.outside="closeList()"
        x-on:keydown="handleKeydown($event)"
        x-bind:id="contentId"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="listbox"
        tabindex="-1"
        data-slot="select-content"
        data-side="{{ $side }}"
        data-align="{{ $align }}"
        data-position="{{ $position }}"
        {{ $attributes->twMerge(['class' => 'fixed z-50 max-h-96 overflow-x-hidden overflow-y-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md']) }}
    >
        {{ $slot }}
    </div>
</template>
