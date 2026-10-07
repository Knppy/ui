@props([
    'value' => null,
    'name' => null,
    'disabled' => false,
])

<span
    x-data="uiSelect(@js($value), @js($disabled))"
    x-modelable="value"
    data-slot="select"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    class="contents"
>
    @if ($name)
        <input type="hidden" name="{{ $name }}" x-bind:value="value ?? ''" @disabled($disabled) />
    @endif
    {{ $slot }}
</span>
