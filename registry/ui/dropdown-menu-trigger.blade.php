<span
    data-slot="dropdown-menu-trigger"
    x-ref="trigger"
    x-init="_trigger = $el"
    @click="toggleMenu()"
    @keydown.down.prevent="openMenu('first')"
    @keydown.up.prevent="openMenu('last')"
    aria-haspopup="menu"
    :aria-expanded="open"
    :data-state="open ? 'open' : 'closed'"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
