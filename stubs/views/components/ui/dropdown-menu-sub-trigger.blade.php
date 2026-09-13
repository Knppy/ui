@props(['inset' => false, 'disabled' => false])

<div
    role="menuitem"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    x-init="registerSubTrigger($el)"
    x-on:click="if (! @js($disabled)) openSub(true)"
    x-on:keydown.arrow-right.prevent.stop="if (! @js($disabled)) openSub(true)"
    x-bind:aria-expanded="open"
    x-bind:data-state="open ? 'open' : 'closed'"
    aria-haspopup="menu"
    data-slot="dropdown-menu-sub-trigger"
    @if ($inset) data-inset @endif
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground"]) }}
>
    {{ $slot }}
    <x-lucide-chevron-right class="ml-auto size-4" />
</div>
