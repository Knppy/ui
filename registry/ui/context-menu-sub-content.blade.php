<div
    x-show="subOpen"
    x-cloak
    @click="subOpen = false"
    data-slot="context-menu-sub-content"
    :data-state="subOpen ? 'open' : 'closed'"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    {{ $attributes->twMerge('absolute top-0 left-full z-50 min-w-32 origin-top-left overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-lg') }}
>
    {{ $slot }}
</div>
