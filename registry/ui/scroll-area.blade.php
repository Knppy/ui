@props([])

<div
    data-slot="scroll-area"
    x-data="uiScrollArea()"
    {{ $attributes->twMerge('relative') }}
>
    <div
        data-slot="scroll-area-viewport"
        x-ref="viewport"
        tabindex="0"
        @scroll="onScroll()"
        @mouseenter="showScrollbar()"
        @mouseleave="scheduleHide()"
        class="size-full rounded-[inherit] transition-[color,box-shadow] outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 overflow-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
    >
        {{ $slot }}
    </div>

    <x-ui.scroll-bar orientation="vertical" />
    <x-ui.scroll-bar orientation="horizontal" />

    <div data-slot="scroll-area-corner"></div>
</div>
