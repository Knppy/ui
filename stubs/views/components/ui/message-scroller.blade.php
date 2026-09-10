<div
    x-data="uiMessageScroller"
    data-slot="message-scroller"
    {{ $attributes->twMerge(['class' => 'group/message-scroller relative flex size-full min-h-0 flex-col overflow-hidden']) }}
>
    {{ $slot }}
</div>
