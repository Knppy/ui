<span
    x-init="
        const close = $el.firstElementChild;
        close?.setAttribute('data-slot', 'sheet-close');
        if (close instanceof HTMLButtonElement && ! close.hasAttribute('type')) close.type = 'button';
    "
    x-on:click="closeDialog()"
    data-slot="sheet-close"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
