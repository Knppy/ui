@props([
    'align' => 'start',
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
        x-ui-dialog-layer
        x-ref="menu"
        x-init="_menu = $el"
        {!! 'x-ui-anchor.' . $placement . '.offset.' . $sideOffset . '="$refs.trigger"' !!}
        @click.outside="! $refs.trigger?.contains($event.target) && closeMenu()"
        @keydown.escape.window="closeMenu()"
        @keydown.arrow-down.prevent="
            $uiNav($event, { selector: '[role=menuitem],[role=menuitemcheckbox],[role=menuitemradio]' })
        "
        @keydown.arrow-up.prevent="
            $uiNav($event, { selector: '[role=menuitem],[role=menuitemcheckbox],[role=menuitemradio]', dir: -1 })
        "
        data-slot="dropdown-menu-content"
        role="menu"
        tabindex="-1"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 max-h-(--radix-dropdown-menu-content-available-height) min-w-32 origin-(--radix-dropdown-menu-content-transform-origin) overflow-x-hidden overflow-y-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95') }}
    >
        {{ $slot }}
    </div>
</template>
