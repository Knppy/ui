@props([
    'autoScroll' => false,
])

<div
    data-slot="message-scroller"
    x-data="uiMessageScroller({ autoScroll: @js((bool) $autoScroll) })"
    {{ $attributes->twMerge('group/message-scroller relative flex size-full min-h-0 flex-col overflow-hidden') }}
>
    {{ $slot }}
</div>
