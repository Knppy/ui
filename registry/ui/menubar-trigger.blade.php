@props([])

<span
    data-slot="menubar-trigger"
    x-ref="trigger"
    x-init="
        _trigger = $el;
        $nextTick(() => $dispatch('menubar:register', $el));
    "
    @click="toggleMenu()"
    @keydown.down.prevent="openMenu('first')"
    @keydown.up.prevent="openMenu('last')"
    @keydown.right.prevent="$dispatch('menubar:move', { el: $el, dir: 1 })"
    @keydown.left.prevent="$dispatch('menubar:move', { el: $el, dir: -1 })"
    role="menuitem"
    aria-haspopup="menu"
    :aria-expanded="open"
    :data-state="open ? 'open' : 'closed'"
    tabindex="0"
    {{ $attributes->twMerge('flex items-center rounded-sm px-2 py-1 text-sm font-medium outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground') }}
>
    {{ $slot }}
</span>
