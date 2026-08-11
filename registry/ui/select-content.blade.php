@props([
    'position' => 'item-aligned',
    'align' => 'start',
    'alignOffset' => 0,
    'side' => 'bottom',
    'sideOffset' => 4,
    'avoidCollisions' => true,
])

@php
    $isPopper = $position === 'popper';
    $placement = $side.($align === 'center' ? '' : '-'.$align);
    $anchorAttr = $isPopper
        ? 'x-ui-anchor.'.$placement.'.offset.'.$sideOffset.'="$refs.trigger"'
        : 'x-ui-item-aligned="$refs.trigger"';

    $classes = [
        'relative z-50 max-h-96 min-w-[8rem] origin-top overflow-x-hidden overflow-y-auto rounded-md border bg-popover text-popover-foreground shadow-md data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
    ];

    if ($isPopper) {
        $classes[] = 'data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1';
    }
@endphp

<template x-teleport="body" wire:ignore>
    <div
        x-ui-dialog-layer
        x-show="open"
        x-cloak
        x-init="_list = $el"
        {!! $anchorAttr !!}
        @if ($isPopper && $alignOffset !== 0) data-align-offset="{{ $alignOffset }}" @endif
        @if ($isPopper && ! $avoidCollisions) data-avoid-collisions="false" @endif
        @click.outside="close(false)"
        @keydown.escape.prevent.stop="close()"
        @keydown.tab.prevent.stop="close()"
        @keydown.enter.prevent.stop="document.activeElement?.click()"
        @keydown.space.prevent.stop="document.activeElement?.click()"
        @keydown="
            $uiNav($event, { selector: '[role=option]' });
            $uiType($event, '[role=option]');
        "
        :id="$id('ui-listbox')"
        role="listbox"
        tabindex="-1"
        data-slot="select-content"
        @if ($isPopper) data-side="{{ $side }}" data-align="{{ $align }}" @endif
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge($classes) }}
    >
        <div data-slot="select-viewport" class="p-1">{{ $slot }}</div>
    </div>
</template>
