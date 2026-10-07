@props(['open' => false])

<span
    x-data="uiDropdownMenu(@js($open))"
    x-modelable="open"
    x-on:keydown.escape.window="closeMenu()"
    data-slot="dropdown-menu"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
