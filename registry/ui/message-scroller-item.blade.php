@props([
    'scrollAnchor' => false,
    'messageId' => null,
])

<div
    data-slot="message-scroller-item"
    @if ($messageId) data-message-id="{{ $messageId }}" @endif
    data-scroll-anchor="{{ $scrollAnchor ? 'true' : 'false' }}"
    {{ $attributes->twMerge('min-w-0 shrink-0 [contain-intrinsic-size:auto_10rem] [content-visibility:auto]') }}
>
    {{ $slot }}
</div>
