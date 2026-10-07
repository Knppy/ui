<div
    x-data="uiCommand"
    x-init="initialize($el)"
    data-slot="command"
    {{ $attributes->twMerge(['class' => 'flex h-full w-full flex-col overflow-hidden rounded-md bg-popover text-popover-foreground']) }}
>
    {{ $slot }}
</div>
