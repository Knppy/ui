@props(['value' => (string) str()->uuid()])

<div
    x-data="{ menu: @js($value) }"
    data-slot="menubar-menu"
    data-value="{{ $value }}"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</div>
