@props(['inset' => false, 'variant' => 'default', 'disabled' => false])

<div
    role="menuitem"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    x-on:click="selectItem($el)"
    data-slot="menubar-item"
    data-variant="{{ $variant }}"
    @if ($inset) data-inset @endif
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[variant=destructive]:text-destructive data-[variant=destructive]:focus:bg-destructive/10 data-[variant=destructive]:focus:text-destructive dark:data-[variant=destructive]:focus:bg-destructive/20 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 ".($inset ? 'pl-8' : '')]) }}
>
    {{ $slot }}
</div>
