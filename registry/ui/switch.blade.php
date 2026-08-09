@props([
    'id' => null,
    'name' => null,
    'value' => 'on',
    'checked' => false,
    'disabled' => false,
    'size' => 'default',
])

@php
    use Illuminate\View\ComponentAttributeBag;

    $wireModel = ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';

    if ($hasWire) {
        $attributes = $attributes->whereDoesntStartWith('wire:model');
    }
@endphp

<button
    type="button"
    role="switch"
    @if ($id) id="{{ $id }}" @endif
    x-data="{ checked: @if ($hasWire)@entangle($wireModel)@else @js((bool) $checked)@endif }"
    :data-state="checked ? 'checked' : 'unchecked'"
    :aria-checked="checked"
    @click="checked = ! checked"
    @if ($disabled) disabled @endif
    data-slot="switch"
    data-size="{{ $size }}"
    {{ $attributes->twMerge("peer group/switch inline-flex shrink-0 items-center rounded-full border border-transparent shadow-xs transition-all outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 data-[size=default]:h-[1.15rem] data-[size=default]:w-8 data-[size=sm]:h-3.5 data-[size=sm]:w-6 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input dark:data-[state=unchecked]:bg-input/80") }}
>
    <span
        data-slot="switch-thumb"
        :data-state="checked ? 'checked' : 'unchecked'"
        class="bg-background dark:data-[state=checked]:bg-primary-foreground dark:data-[state=unchecked]:bg-foreground pointer-events-none block rounded-full ring-0 transition-transform group-data-[size=default]/switch:size-4 group-data-[size=sm]/switch:size-3 data-[state=checked]:translate-x-[calc(100%-2px)] data-[state=unchecked]:translate-x-0"
    ></span>
    @if ($name)
        <input type="hidden" :name="checked ? @js($name) : null" value="{{ $value }}" />
    @endif
</button>
