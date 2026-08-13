@props([
    'name' => null,
    'value' => '',
    'multiple' => false,
])

@php
    use Illuminate\View\ComponentAttributeBag;

    $initialValue = $multiple
        ? collect(is_array($value) ? $value : (($value === '' || $value === null) ? [] : [$value]))->map(fn ($v) => (string) $v)->values()
        : (string) $value;

    $wireModel = ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';
    if ($hasWire) {
        $attributes = $attributes->whereDoesntStartWith('wire:model');
    }
@endphp

<div
    data-slot="combobox"
    x-data="uiListbox({
        multiple: @js((bool) $multiple),
        value: @if ($hasWire)@entangle($wireModel)@else @js($initialValue)@endif,
    })"
    x-id="['ui-combobox-list', 'ui-combobox-opt']"
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
