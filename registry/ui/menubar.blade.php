@props([])

<div
    data-slot="menubar"
    x-data="uiMenubar()"
    @menubar:register.stop="registerTrigger($event.detail)"
    @menubar:move.stop="moveTrigger($event.detail.el, $event.detail.dir)"
    role="menubar"
    {{ $attributes->twMerge('flex h-9 items-center gap-1 rounded-md border bg-background p-1 shadow-xs') }}
>
    {{ $slot }}
</div>
