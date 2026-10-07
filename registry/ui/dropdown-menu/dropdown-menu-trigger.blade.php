<span
    x-init="registerTrigger($el.firstElementChild)"
    x-effect="trigger?.setAttribute('aria-expanded', open)"
    x-on:click="toggleMenu()"
    x-on:keydown="handleTriggerKeydown($event)"
    aria-haspopup="menu"
    data-slot="dropdown-menu-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
