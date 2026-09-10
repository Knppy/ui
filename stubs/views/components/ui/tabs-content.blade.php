@props(['value'])

<div
    x-cloak
    x-show="value === @js($value)"
    role="tabpanel"
    tabindex="0"
    x-bind:id="contentId(@js($value))"
    x-bind:aria-labelledby="triggerId(@js($value))"
    x-bind:data-state="value === @js($value) ? 'active' : 'inactive'"
    data-slot="tabs-content"
    {{ $attributes->twMerge(['class' => 'flex-1 outline-none']) }}
>
    {{ $slot }}
</div>
