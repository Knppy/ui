@props(['value', 'disabled' => false, 'textValue' => null])

@php($label = $textValue ?? trim($slot))

<div
    role="option"
    x-show="matches($el)"
    x-bind:id="optionId(@js($value))"
    x-on:click="selectOption($el)"
    x-on:mousemove="if (! @js($disabled)) activeIndex = visibleOptions.indexOf($el)"
    x-bind:aria-selected="isSelected(@js($value))"
    x-bind:data-highlighted="visibleOptions[activeIndex] === $el ? '' : null"
    data-slot="combobox-item"
    data-value="{{ $value }}"
    data-label="{{ $label }}"
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except(['disabled', 'textValue'])->twMerge(['class' => 'relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50']) }}
>
    {{ $slot }}
    <span
        x-show="isSelected(@js($value))"
        aria-hidden="true"
        data-slot="combobox-item-indicator"
        class="pointer-events-none absolute right-2 flex size-4 items-center justify-center"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
            <path d="m5 12 4 4L19 6" />
        </svg>
    </span>
</div>
