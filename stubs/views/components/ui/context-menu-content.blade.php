<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition
        x-init="registerContent($el)"
        x-on:click.outside="closeMenu(false)"
        x-on:keydown="handleKeydown($event)"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="menu"
        tabindex="-1"
        data-slot="context-menu-content"
        data-side="bottom"
        {{ $attributes->twMerge(['class' => 'fixed z-50 max-h-[calc(100vh-1rem)] min-w-32 overflow-x-hidden overflow-y-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-hidden']) }}
    >
        {{ $slot }}
    </div>
</template>
