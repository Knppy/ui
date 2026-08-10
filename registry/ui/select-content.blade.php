@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
])

@php
    $placement = $side.($align === 'center' ? '' : '-'.$align);
    $anchorAttr = 'x-ui-anchor.'.$placement.'.offset.'.$sideOffset.'="$refs.trigger"';
@endphp

<template x-teleport="body" wire:ignore>
    <div
        x-ui-dialog-layer
        x-show="open"
        x-cloak
        x-init="_list = $el"
        {!! $anchorAttr !!}
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
        data-side="{{ $side }}"
        data-align="{{ $align === 'center' ? 'center' : $align }}"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('relative z-50 max-h-96 min-w-[8rem] origin-top overflow-x-hidden overflow-y-auto rounded-md border bg-popover text-popover-foreground shadow-md data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95') }}
    >
        <div data-slot="select-viewport" class="p-1">{{ $slot }}</div>
    </div>
</template>
