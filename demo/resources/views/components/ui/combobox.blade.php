@props([
    'value' => null,
    'name' => null,
    'multiple' => false,
    'disabled' => false,
])

<div
    x-data="uiCombobox(@js($value), @js($multiple), @js($disabled))"
    x-modelable="value"
    data-slot="combobox"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    class="contents"
>
    @if ($name)
        @if ($multiple)
            <template x-for="selectedValue in value" :key="selectedValue">
                <input
                    type="hidden"
                    name="{{ str_ends_with($name, '[]') ? $name : $name.'[]' }}"
                    x-bind:value="selectedValue"
                    @disabled($disabled)
                />
            </template>
        @else
            <input type="hidden" name="{{ $name }}" x-bind:value="value ?? ''" @disabled($disabled) />
        @endif
    @endif
    {{ $slot }}
</div>
