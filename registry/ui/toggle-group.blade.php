@php  @endphp
@props([
    'type' => 'single',
    'value' => null,
    'variant' => 'default',
    'size' => 'default',
    'spacing' => 0,
    'orientation' => 'horizontal',
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
    data-slot="toggle-group"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-spacing="{{ $spacing }}"
    role="group"
    data-orientation="{{ $orientation }}"
    @if ($spacing > 0) style="--gap: {{ $spacing }}" @endif
    x-data="{
        type: @js($type),
        value: @if ($hasWire)@entangle($wireModel)@else @js($type === 'multiple' ? (array) ($value ?? []) : $value)@endif,
        rovingValue: null,
        toggle(v) {
            if (this.type === 'multiple') {
                this.value = this.value.includes(v) ? this.value.filter(x => x !== v) : [...this.value, v];
            } else {
                this.value = this.value === v ? null : v;
            }
        },
        isOn(v) {
            return this.type === 'multiple' ? this.value.includes(v) : this.value === v;
        },
    }"
    x-init="
        $nextTick(() => {
            const f = $el.querySelector('[data-slot=toggle-group-item]:not([disabled])');
            rovingValue = f?.getAttribute('data-value') ?? null;
        })
    "
    @keydown="
        if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes($event.key)) {
            $uiNav($event, { selector: '[data-slot=toggle-group-item]', orientation: 'both' });
        }
    "
    {{ $attributes->twMerge('group/toggle-group flex w-fit items-center rounded-md gap-[--spacing(var(--gap))] data-[orientation=vertical]:flex-col data-[orientation=vertical]:items-stretch data-[spacing=0]:data-[variant=outline]:shadow-xs') }}
>
    {{ $slot }}
</div>
