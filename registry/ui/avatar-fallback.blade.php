<div
    data-slot="avatar-fallback"
    {{ $attributes->twMerge('flex size-full items-center justify-center rounded-full bg-muted text-sm text-muted-foreground group-data-[size=sm]/avatar:text-xs') }}
>
    {{ $slot }}
</div>
