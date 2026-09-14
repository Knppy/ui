@props(['value' => null])

<div
    x-data="uiMenubarRadioGroup(@js($value))"
    x-modelable="selectedValue"
    role="group"
    data-slot="menubar-radio-group"
    {{ $attributes }}
>
    {{ $slot }}
</div>
