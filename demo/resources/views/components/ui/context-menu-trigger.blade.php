<div
    x-init="registerTrigger($el)"
    x-on:contextmenu.prevent="openMenu($event)"
    x-on:keydown="handleTriggerKeydown($event)"
    x-bind:aria-expanded="open"
    aria-haspopup="menu"
    tabindex="0"
    data-slot="context-menu-trigger"
    {{ $attributes }}
>
    {{ $slot }}
</div>
