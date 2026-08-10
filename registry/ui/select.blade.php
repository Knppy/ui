@props([
    'name' => null,
    'multiple' => false,
    'value' => '',
])

@php
    use Illuminate\View\ComponentAttributeBag;

    $selectedValues = collect(is_array($value) ? $value : (($value === '' || $value === null) ? [] : [$value]))
        ->map(fn ($v) => (string) $v)
        ->values();
    $initialValue = $multiple ? $selectedValues : (string) $value;

    $wireModel = ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';

    if ($hasWire) {
        $attributes = $attributes->whereDoesntStartWith('wire:model');
    }
@endphp

<div
    data-slot="select"
    x-data="uiSelect({ value: @if ($hasWire)@entangle($wireModel)@else @js($initialValue)@endif, multiple: @js((bool) $multiple)@if ($hasWire), entangled: true @endif })"
    x-id="['ui-listbox']"
    {{ $attributes->twMerge('relative') }}
>
    @if ($name)
        @if ($multiple)
            <template x-for="v in value" :key="v">
                <input type="hidden" name="{{ $name }}[]" :value="v" />
            </template>
        @else
            <input type="hidden" name="{{ $name }}" :value="value" />
        @endif
    @endif
    {{ $slot }}
</div>
