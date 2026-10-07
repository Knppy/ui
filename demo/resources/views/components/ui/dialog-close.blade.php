<span
    x-init="
        const close = $el.firstElementChild;
        close?.setAttribute('data-slot', 'dialog-close');
        if (close instanceof HTMLButtonElement && ! close.hasAttribute('type')) close.type = 'button';
    "
    x-on:click="closeDialog()"
    data-slot="dialog-close"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
