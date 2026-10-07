@props([
    'orientation' => 'vertical',
    'type' => 'hover',
])

<div
    x-data="uiScrollArea"
    x-init="initialize($el)"
    data-slot="scroll-area"
    data-type="{{ $type }}"
    {{ $attributes->twMerge(['class' => 'relative overflow-hidden']) }}
>
    <div
        tabindex="0"
        data-slot="scroll-area-viewport"
        x-on:scroll.passive="update()"
        class="[&::-webkit-scrollbar]:hidden focus-visible:ring-ring/50 size-full [scrollbar-width:none] overflow-auto rounded-[inherit] outline-none focus-visible:ring-[3px] focus-visible:outline-1"
    >
        {{ $slot }}
    </div>
    <x-ui.scroll-bar :orientation="$orientation" />
</div>
