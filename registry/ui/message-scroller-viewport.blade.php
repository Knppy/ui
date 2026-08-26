@props([])

<div
    data-slot="message-scroller-viewport"
    x-ref="viewport"
    role="region"
    aria-label="Messages"
    tabindex="0"
    :data-autoscrolling="! _userScrolled && _autoScroll ? '' : null"
    {{ $attributes->twMerge('size-full min-h-0 min-w-0 scroll-fade-b scrollbar-thin scrollbar-gutter-stable overflow-y-auto overscroll-contain contain-content data-[autoscrolling]:scrollbar-none') }}
>
    {{ $slot }}
</div>
