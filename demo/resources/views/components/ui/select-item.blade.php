@props(['value', 'disabled' => false])

<div
    role="option"
    x-bind:id="optionId(@js($value))"
    x-on:click="selectOption($el)"
    x-on:mousemove="if (! @js($disabled)) activeIndex = selectableOptions.indexOf($el)"
    x-bind:aria-selected="isSelected(@js($value))"
    x-bind:data-highlighted="selectableOptions[activeIndex] === $el ? '' : null"
    x-bind:data-state="isSelected(@js($value)) ? 'checked' : 'unchecked'"
    data-slot="select-item"
    data-value="{{ $value }}"
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground"]) }}
>
    <span
        x-show="isSelected(@js($value))"
        aria-hidden="true"
        data-slot="select-item-indicator"
        class="absolute right-2 flex size-3.5 items-center justify-center"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
            <path d="m5 12 4 4L19 6" />
        </svg>
    </span>
    <span data-slot="select-item-text">{{ $slot }}</span>
</div>
