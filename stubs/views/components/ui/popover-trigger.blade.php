<span
    x-init="registerTrigger($el.firstElementChild)"
    x-effect="trigger?.setAttribute('aria-expanded', open)"
    x-on:click="toggle()"
    data-slot="popover-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
