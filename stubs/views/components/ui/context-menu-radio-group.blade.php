@props(['value' => null])

<div
    x-data="uiContextMenuRadioGroup(@js($value))"
    x-modelable="value"
    role="group"
    data-slot="context-menu-radio-group"
    {{ $attributes }}
>
    {{ $slot }}
</div>
