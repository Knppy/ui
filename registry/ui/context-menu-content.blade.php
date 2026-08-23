<template x-teleport="body">
    <div
        x-show="open"
        x-cloak
        x-ui-dialog-layer
        x-ref="menu"
        x-init="_menu = $el"
        @click.outside="closeMenu()"
        @keydown.escape.window="closeMenu()"
        @keydown.arrow-down.prevent="
            $uiNav($event, { selector: '[role=menuitem],[role=menuitemcheckbox],[role=menuitemradio]' })
        "
        @keydown.arrow-up.prevent="
            $uiNav($event, { selector: '[role=menuitem],[role=menuitemcheckbox],[role=menuitemradio]', dir: -1 })
        "
        :style="open ? `position:fixed;left:${x}px;top:${y}px` : ''"
        data-slot="context-menu-content"
        role="menu"
        tabindex="-1"
        :data-state="open ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 max-h-(--radix-context-menu-content-available-height) min-w-32 overflow-visible rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-none') }}
    >
        {{ $slot }}
    </div>
</template>
