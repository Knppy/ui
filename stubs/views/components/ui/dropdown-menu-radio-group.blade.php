@props(['value' => null])

<div
    x-data="uiDropdownMenuRadioGroup(@js($value))"
    x-modelable="value"
    role="group"
    data-slot="dropdown-menu-radio-group"
    {{ $attributes }}
>
    {{ $slot }}
</div>
