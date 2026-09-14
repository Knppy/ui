@props(['open' => false])

<span
    x-data="uiContextMenu(@js($open))"
    x-modelable="open"
    x-on:keydown.escape.window="closeMenu()"
    x-on:resize.window="if (open) positionContent();"
    data-slot="context-menu"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
