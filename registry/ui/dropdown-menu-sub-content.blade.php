<template x-teleport="body">
    <div
        x-show="subOpen"
        x-cloak
        x-ui-dialog-layer
        x-ui-anchor.right-start.offset.4="$refs.subTrigger"
        @mouseenter="clearTimeout(_subCloseTimer)"
        @mouseleave="subOpen = false"
        data-slot="dropdown-menu-sub-content"
        :data-state="subOpen ? 'open' : 'closed'"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 min-w-32 origin-(--radix-dropdown-menu-content-transform-origin) overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-lg data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95') }}
    >
        {{ $slot }}
    </div>
</template>
