<div
    x-cloak
    x-show="isOpen($el.dataset.value)"
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="translate-x-2 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-2 opacity-0"
    x-init="registerContent($el)"
    x-on:pointerenter="clearTimeout(closeTimer)"
    x-on:pointerleave="scheduleClose()"
    x-on:keydown="handleContentKeydown($event)"
    x-bind:data-state="isOpen($el.dataset.value) ? 'open' : 'closed'"
    data-slot="navigation-menu-content"
    {{ $attributes->twMerge(['class' => 'top-0 left-0 z-50 w-max min-w-48 bg-popover p-2 pr-2.5 text-popover-foreground outline-hidden md:absolute group-data-[viewport=false]/navigation-menu:top-full group-data-[viewport=false]/navigation-menu:mt-1.5 group-data-[viewport=false]/navigation-menu:overflow-hidden group-data-[viewport=false]/navigation-menu:rounded-md group-data-[viewport=false]/navigation-menu:border group-data-[viewport=false]/navigation-menu:shadow']) }}
>
    {{ $slot }}
</div>
