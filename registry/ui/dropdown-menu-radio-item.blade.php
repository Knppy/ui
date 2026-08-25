@props([
    'checked' => false,
    'disabled' => false,
])

<div
    data-slot="dropdown-menu-radio-item"
    role="menuitemradio"
    :aria-checked="@js((bool) $checked)"
    @if ($disabled) data-disabled="true" aria-disabled="true" @else @click="typeof closeMenu === 'function' && closeMenu()" @endif
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
    {{ $attributes->twMerge('relative flex cursor-default items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4') }}
>
    <span class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        @if ($checked)
            <x-lucide-circle class="size-2 fill-current" />
        @endif
    </span>
    {{ $slot }}
</div>
