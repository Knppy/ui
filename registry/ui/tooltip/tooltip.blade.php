@props(['delayDuration' => null])

<span
    x-data="uiTooltip(@js($delayDuration) ?? $data.uiTooltipDelay ?? 0)"
    x-on:keydown.escape.window="hide()"
    data-slot="tooltip"
    {{ $attributes }}
>
    {{ $slot }}
</span>
