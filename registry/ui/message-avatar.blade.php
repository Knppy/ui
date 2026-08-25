<div
    data-slot="message-avatar"
    {{ $attributes->twMerge('flex w-fit min-w-8 shrink-0 items-center justify-center self-end overflow-hidden rounded-full bg-muted group-has-data-[slot=message-footer]/message:-translate-y-8') }}
>
    {{ $slot }}
</div>
