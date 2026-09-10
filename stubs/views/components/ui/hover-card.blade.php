@props(['openDelay' => 700, 'closeDelay' => 300])

<span
    x-data="uiHoverCard({{ (int) $openDelay }}, {{ (int) $closeDelay }})"
    x-on:keydown.escape.window="open = false"
    data-slot="hover-card"
    {{ $attributes }}
>
    {{ $slot }}
</span>
