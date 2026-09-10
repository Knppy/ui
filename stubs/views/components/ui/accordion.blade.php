@props([
    'value' => null,
    'type' => 'single',
    'collapsible' => true,
])

<div
    x-data="uiAccordion(@js($value), @js($type), @js($collapsible))"
    x-modelable="value"
    data-slot="accordion"
    {{ $attributes }}
>
    {{ $slot }}
</div>
