@props([
    'checked' => false,
    'disabled' => false,
    'indeterminate' => false,
    'id' => null,
    'name' => null,
    'native' => false,
    'value' => 'on',
])

@php
    use Illuminate\View\ComponentAttributeBag;

    $wireModel = ComponentAttributeBag::hasMacro('wire') ? $attributes->wire('model') : null;
    $hasWire = $wireModel && is_string($wireModel->value()) && $wireModel->value() !== '';

    if (! $native && $hasWire) {
        $attributes = $attributes->whereDoesntStartWith('wire:model');
    }
@endphp

@if ($native)
    <input
        type="checkbox"
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        @checked($checked)
        @disabled($disabled)
        data-slot="checkbox"
        {{ $attributes->twMerge('ui-checkbox') }}
    />
@else
    <button
        data-slot="checkbox"
        type="button"
        role="checkbox"
        @if ($id) id="{{ $id }}" @endif
        x-data="{ checked: @if ($hasWire)@entangle($wireModel)@else @js((bool) $checked)@endif, indeterminate: @js((bool) $indeterminate) }"
        :data-state="indeterminate ? 'indeterminate' : checked ? 'checked' : 'unchecked'"
        :aria-checked="indeterminate ? 'mixed' : checked.toString()"
        @click="indeterminate ? ((indeterminate = false), (checked = true)) : (checked = ! checked)"
        @if ($disabled) disabled @endif
        {{ $attributes->twMerge('peer border-input dark:bg-input/30 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground dark:data-[state=checked]:bg-primary data-[state=checked]:border-primary data-[state=indeterminate]:bg-primary data-[state=indeterminate]:text-primary-foreground data-[state=indeterminate]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 flex items-center justify-center') }}
    >
        <span
            data-slot="checkbox-indicator"
            class="flex items-center justify-center text-current transition-none"
            x-show="checked || indeterminate"
            x-cloak
        >
            <x-lucide-minus class="size-3.5" x-show="indeterminate" aria-hidden="true" />
            <x-lucide-check class="size-3.5" x-show="! indeterminate" aria-hidden="true" />
        </span>
        @if ($name)
            <input type="hidden" :name="checked && ! indeterminate ? @js($name) : null" value="{{ $value }}" />
        @endif
    </button>
@endif
