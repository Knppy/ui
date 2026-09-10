@props([
    'value' => null,
    'type' => 'single',
    'multiple' => false,
    'variant' => 'default',
    'size' => 'default',
    'spacing' => 0,
])

@php
    $type = $multiple ? 'multiple' : $type;
@endphp

<div
    x-data="uiToggleGroup(@js($value), @js($type))"
    x-modelable="value"
    role="group"
    data-slot="toggle-group"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-spacing="{{ $spacing }}"
    style="--toggle-group-gap: calc({{ $spacing }} * 0.25rem)"
    {{ $attributes->except('multiple')->twMerge(['class' => 'group/toggle-group flex w-fit items-center gap-[var(--toggle-group-gap)] rounded-md data-[spacing=0]:data-[variant=outline]:shadow-xs']) }}
>
    {{ $slot }}
</div>
