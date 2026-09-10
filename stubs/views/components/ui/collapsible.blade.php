@props(['open' => false])

<div
    x-data="uiCollapsible(@js($open))"
    x-modelable="open"
    x-bind:data-state="open ? 'open' : 'closed'"
    data-slot="collapsible"
    {{ $attributes }}
>
    {{ $slot }}
</div>
