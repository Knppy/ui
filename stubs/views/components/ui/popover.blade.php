@props(['open' => false])

<span
    x-data="uiPopover(@js($open))"
    x-modelable="open"
    x-on:keydown.escape.window="open = false"
    data-slot="popover"
    {{ $attributes }}
>
    {{ $slot }}
</span>
