@props([
    'open' => false,
    'direction' => 'bottom',
    'dismissible' => true,
])

<div
    x-data="uiDrawer(@js($open), @js($direction), @js($dismissible))"
    x-modelable="open"
    x-on:keydown.escape.window="if (dismissible) closeDrawer();"
    data-slot="drawer"
    {{ $attributes }}
>
    {{ $slot }}
</div>
