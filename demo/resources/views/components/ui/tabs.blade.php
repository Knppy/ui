@props([
    'value' => null,
    'orientation' => 'horizontal',
])

<div
    x-data="uiTabs(@js($value), @js($orientation))"
    x-modelable="value"
    data-slot="tabs"
    data-orientation="{{ $orientation }}"
    {{ $attributes->twMerge(['class' => 'group/tabs flex gap-2 data-[orientation=horizontal]:flex-col']) }}
>
    {{ $slot }}
</div>
