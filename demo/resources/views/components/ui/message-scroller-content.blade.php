<div
    data-slot="message-scroller-content"
    {{ $attributes->twMerge(['class' => 'flex h-max min-h-full flex-col gap-8']) }}
>
    {{ $slot }}
</div>
