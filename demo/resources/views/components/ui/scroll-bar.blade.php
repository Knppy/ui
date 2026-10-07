@props(['orientation' => 'vertical'])

<div
    aria-hidden="true"
    data-slot="scroll-area-scrollbar"
    data-orientation="{{ $orientation }}"
    x-on:pointerdown="startDrag($event, @js($orientation))"
    {{ $attributes->twMerge(['class' => 'absolute flex touch-none p-px transition-colors select-none data-[orientation=horizontal]:inset-x-0 data-[orientation=horizontal]:bottom-0 data-[orientation=horizontal]:h-2.5 data-[orientation=horizontal]:flex-col data-[orientation=vertical]:inset-y-0 data-[orientation=vertical]:end-0 data-[orientation=vertical]:w-2.5']) }}
>
    <div data-slot="scroll-area-thumb" class="bg-border relative flex-1 rounded-full"></div>
</div>
