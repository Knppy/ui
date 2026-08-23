<div
    data-slot="command"
    x-data="uiCommand()"
    x-id="['ui-command-list']"
    x-init="
        $nextTick(() => ensureActive());
        $watch('query', () => $nextTick(() => ensureActive()));
    "
    {{ $attributes->twMerge('bg-popover text-popover-foreground flex h-full w-full flex-col overflow-hidden rounded-md') }}
>
    {{ $slot }}
</div>
