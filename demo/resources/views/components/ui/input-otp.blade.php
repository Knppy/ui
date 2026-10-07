@props([
    'value' => '',
    'maxlength' => 6,
    'inputmode' => 'text',
    'containerClass' => null,
])

<div
    x-data="uiInputOtp(@js($value), @js($maxlength))"
    x-modelable="value"
    data-slot="input-otp"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    {{ $attributes->only('class')->twMerge(['class' => 'relative flex items-center gap-2 has-disabled:opacity-50', $containerClass]) }}
>
    <input
        x-ref="input"
        x-bind:value="value"
        x-on:input="update($event)"
        x-on:focus="
            focused = true;
            updateSelection($event);
        "
        x-on:blur="focused = false"
        x-on:click="updateSelection($event)"
        x-on:keyup="updateSelection($event)"
        type="text"
        value="{{ $value }}"
        maxlength="{{ $maxlength }}"
        autocomplete="one-time-code"
        inputmode="{{ $inputmode }}"
        {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->except('class')->twMerge(['class' => 'pointer-events-none absolute inset-0 size-full opacity-0 disabled:cursor-not-allowed']) }}
    />
    {{ $slot }}
</div>
