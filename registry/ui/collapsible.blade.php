@props([
    'open' => false,
])

<div
    data-slot="collapsible"
    x-data="{ open: @js((bool) $open) }"
    :data-state="open ? 'open' : 'closed'"
    {{ $attributes }}
>
    {{ $slot }}
</div>
