<div
    x-init="initViewport($el)"
    x-on:scroll.passive="updateScrollState()"
    data-slot="message-scroller-viewport"
    {{ $attributes->twMerge(['class' => 'size-full min-h-0 min-w-0 overflow-y-auto overscroll-contain [scrollbar-gutter:stable] [scrollbar-width:thin] [contain:content]']) }}
>
    {{ $slot }}
</div>
