<span
    x-init="
        const close = $el.firstElementChild;
        close?.setAttribute('data-slot', 'drawer-close');
        if (close instanceof HTMLButtonElement && ! close.hasAttribute('type')) close.type = 'button';
    "
    x-on:click="closeDrawer()"
    data-slot="drawer-close"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
