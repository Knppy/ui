@props([
    'checked' => false,
    'disabled' => false,
])

<div
    data-slot="menubar-checkbox-item"
    role="menuitemcheckbox"
    :aria-checked="@js((bool) $checked)"
    @if ($disabled) data-disabled="true" aria-disabled="true" @else @click="closeMenu()" @endif
    tabindex="{{ $disabled ? '-1' : '0' }}"
    @keydown.enter.prevent="
        if (! $el.dataset.disabled) {
            $el.click();
            closeMenu();
        }
    "
    @keydown.space.prevent="
        if (! $el.dataset.disabled) {
            $el.click();
            closeMenu();
        }
    "
    {{ $attributes->twMerge('relative flex cursor-default items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4') }}
>
    <span class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        @if ($checked)
            <x-lucide-check class="size-4" />
        @endif
    </span>
    {{ $slot }}
</div>
