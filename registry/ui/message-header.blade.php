<div
    data-slot="message-header"
    {{ $attributes->twMerge('flex max-w-full min-w-0 items-center px-3 text-xs font-medium text-muted-foreground group-has-data-[variant=ghost]/message:px-0') }}
>
    {{ $slot }}
</div>
