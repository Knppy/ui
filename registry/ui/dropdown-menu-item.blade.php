@props([
    'inset' => false,
    'variant' => 'default',
    'disabled' => false,
])

<div
    data-slot="dropdown-menu-item"
    @if ($inset) data-inset="true" @endif
    data-variant="{{ $variant }}"
    @if ($disabled) data-disabled="true" aria-disabled="true" @else @click="
        typeof closeMenu === 'function' && closeMenu()
    " @endif
    role="menuitem"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    @keydown.enter.prevent="
        if (! $el.dataset.disabled) {
            $el.click();
            typeof closeMenu === 'function' && closeMenu();
        }
    "
    @keydown.space.prevent="
        if (! $el.dataset.disabled) {
            $el.click();
            typeof closeMenu === 'function' && closeMenu();
        }
    "
    {{ $attributes->twMerge('relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[inset]:pl-8 data-[variant=destructive]:text-destructive data-[variant=destructive]:hover:bg-destructive/10 data-[variant=destructive]:hover:text-destructive data-[variant=destructive]:focus:bg-destructive/10 data-[variant=destructive]:focus:text-destructive dark:data-[variant=destructive]:hover:bg-destructive/20 dark:data-[variant=destructive]:focus:bg-destructive/20 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground') }}
>
    {{ $slot }}
</div>
