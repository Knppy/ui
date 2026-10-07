@props(['scrollAnchor' => false])

<div
    data-slot="message-scroller-item"
    @if ($scrollAnchor) data-scroll-anchor @endif
    {{ $attributes->twMerge(['class' => 'min-w-0 shrink-0 [contain-intrinsic-size:auto_10rem] [content-visibility:auto]']) }}
>
    {{ $slot }}
</div>
