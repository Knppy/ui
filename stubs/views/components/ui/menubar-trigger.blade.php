@props(['disabled' => false])

<button
    type="button"
    x-init="registerTrigger(menu, $el)"
    x-bind:aria-expanded="isOpen(menuValue($el))"
    x-bind:data-state="isOpen(menu) ? 'open' : 'closed'"
    x-on:click="if (! @js($disabled)) toggleMenu(menu)"
    x-on:mouseenter="if (activeMenu && ! @js($disabled)) openMenu(menu)"
    x-on:keydown="handleTriggerKeydown($event, menu)"
    role="menuitem"
    aria-haspopup="menu"
    data-slot="menubar-trigger"
    @if ($disabled) data-disabled aria-disabled="true" disabled @endif
    {{ $attributes->except('disabled')->twMerge(['class' => 'flex items-center rounded-sm px-2 py-1 text-sm font-medium outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground disabled:pointer-events-none disabled:opacity-50']) }}
>
    {{ $slot }}
</button>
