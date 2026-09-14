@props(['value', 'disabled' => false])

<div
    role="menuitemradio"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    x-on:click="if (! @js($disabled)) selectValue(@js($value))"
    x-bind:aria-checked="value === @js($value)"
    x-bind:data-state="value === @js($value) ? 'checked' : 'unchecked'"
    data-slot="context-menu-radio-item"
    data-value="{{ $value }}"
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "relative flex cursor-default items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"]) }}
>
    <span aria-hidden="true" class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <svg x-show="value === @js($value)" viewBox="0 0 8 8" fill="currentColor" class="size-2"><circle cx="4" cy="4" r="3" /></svg>
    </span>
    {{ $slot }}
</div>
