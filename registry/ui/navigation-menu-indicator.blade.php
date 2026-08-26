@props([])

<div
    data-slot="navigation-menu-indicator"
    x-show="viewportOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    :style="indicatorStyle()"
    {{ $attributes->twMerge('top-full z-[1] flex h-1.5 items-end justify-center overflow-hidden') }}
>
    <div class="bg-border relative top-[60%] h-2 w-2 rotate-45 rounded-tl-sm shadow-md"></div>
</div>
