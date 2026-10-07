<template x-teleport="body">
    <div
        x-cloak
        x-show="open && isOpen(menu)"
        x-init="registerSubContent($el)"
        x-anchor.right-start.offset.4="trigger"
        x-on:mouseenter="openSub()"
        x-on:mouseleave="scheduleSubClose()"
        x-on:keydown.stop="handleSubKeydown($event)"
        x-on:keydown.arrow-left.prevent.stop="closeSub(true)"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="menu"
        tabindex="-1"
        data-side="right"
        data-slot="menubar-sub-content"
        {{ $attributes->twMerge(['class' => 'z-50 min-w-32 overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-lg outline-hidden']) }}
    >
        {{ $slot }}
    </div>
</template>
