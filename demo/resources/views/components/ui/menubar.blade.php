@props(['value' => null])

<div
    x-data="uiMenubar(@js($value))"
    x-modelable="value"
    x-on:keydown="handleRootKeydown($event)"
    x-on:keydown.escape.window="closeMenu()"
    role="menubar"
    data-slot="menubar"
    {{ $attributes->twMerge(['class' => 'flex h-9 items-center gap-1 rounded-md border bg-background p-1 shadow-xs']) }}
>
    {{ $slot }}
</div>
