@props([])

<div
    data-slot="message-scroller-content"
    x-ref="content"
    role="log"
    aria-relevant="additions"
    {{ $attributes->twMerge('flex h-max min-h-full flex-col gap-8') }}
>
    {{ $slot }}
</div>
