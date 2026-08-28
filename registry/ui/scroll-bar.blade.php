@props([
    'orientation' => 'vertical',
])

<div
    data-slot="scroll-area-scrollbar"
    data-orientation="{{ $orientation }}"
    x-show="_scrollbarVisible && {{ $orientation === 'vertical' ? '_hasVerticalScroll' : '_hasHorizontalScroll' }}"
    x-transition:enter="transition-opacity duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @mouseenter="cancelHide()"
    @mouseleave="scheduleHide()"
    @mousedown.prevent="startDrag($event, '{{ $orientation }}')"
    {{ $attributes->twMerge(
        'flex touch-none p-px transition-colors select-none absolute' .
        ($orientation === 'vertical'
            ? ' right-0 top-0 h-full w-2.5 border-l border-l-transparent'
            : ' bottom-0 left-0 w-full h-2.5 flex-col border-t border-t-transparent')
    ) }}
>
    <div
        data-slot="scroll-area-thumb"
        x-ref="{{ $orientation === 'vertical' ? 'thumbY' : 'thumbX' }}"
        class="relative flex-1 rounded-full bg-border"
        :style="{{ $orientation === 'vertical'
            ? '{ height: _thumbSizeY + \'%\', transform: \'translateY(\' + _thumbOffsetY + \'%)\' }'
            : '{ width: _thumbSizeX + \'%\', transform: \'translateX(\' + _thumbOffsetX + \'%)\' }' }}"
    ></div>
</div>
