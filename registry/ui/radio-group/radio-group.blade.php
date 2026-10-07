@props([
    'value' => null,
    'name' => null,
])

<div
    x-data="uiRadioGroup(@js($value))"
    x-modelable="value"
    role="radiogroup"
    data-slot="radio-group"
    data-name="{{ $name }}"
    {{ $attributes->twMerge(['class' => 'grid gap-3']) }}
>
    {{ $slot }}
</div>
