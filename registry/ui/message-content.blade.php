<div
    data-slot="message-content"
    {{ $attributes->twMerge('flex w-full min-w-0 flex-col gap-2.5 wrap-break-word group-data-[align=end]/message:*:data-slot:self-end') }}
>
    {{ $slot }}
</div>
