@props([
    'name' => null,
    'defaultValue' => null,
])

@php
    use Illuminate\View\ComponentAttributeBag;

    $wireModel = ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';

    if ($hasWire) {
        $attributes = $attributes->whereDoesntStartWith('wire:model');
    }
@endphp

<div
    data-slot="radio-group"
    role="radiogroup"
    x-data="{ value: @if ($hasWire)@entangle($wireModel)@else @js($defaultValue)@endif, rovingValue: @js($defaultValue) }"
    x-init="
        $nextTick(() => {
            if (rovingValue === null) {
                const f = $el.querySelector('[role=radio]:not([disabled])');
                rovingValue = f?.getAttribute('data-value') ?? null;
            }
        })
    "
    @keydown="
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes($event.key)) {
            $uiNav($event, { selector: '[role=radio]', orientation: 'both' });
            const v = document.activeElement?.getAttribute('data-value');
            if (v != null) {
                value = v;
                rovingValue = v;
            }
        }
    "
    {{ $attributes->twMerge('grid gap-3') }}
>
    @if ($name)
        <input type="hidden" name="{{ $name }}" :value="value" />
    @endif
    {{ $slot }}
</div>
