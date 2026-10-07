<div
    x-cloak
    x-show="open"
    x-bind:data-state="open ? 'open' : 'closed'"
    data-slot="collapsible-content"
    {{ $attributes }}
>
    {{ $slot }}
</div>
