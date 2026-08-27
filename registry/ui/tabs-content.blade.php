@props([
    'value' => null,
])

<div
    data-slot="tabs-content"
    role="tabpanel"
    :id="$id('ui-tabs-panel', @js($value))"
    :aria-labelledby="$id('ui-tabs-trigger', @js($value))"
    x-show="isActive(@js($value))"
    x-cloak
    :data-state="isActive(@js($value)) ? 'active' : 'inactive'"
    :tabindex="isActive(@js($value)) ? 0 : -1"
    {{ $attributes->twMerge('flex-1 outline-none') }}
>
    {{ $slot }}
</div>
