@props(['delayDuration' => 0])

<span
    x-data="{ uiTooltipDelay: {{ (int) $delayDuration }} }"
    data-slot="tooltip-provider"
    {{ $attributes }}
>{{ $slot }}</span>
