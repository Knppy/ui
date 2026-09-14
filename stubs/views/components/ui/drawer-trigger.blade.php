<span
    x-init="registerTrigger($el.firstElementChild)"
    x-effect="
        trigger?.setAttribute('aria-expanded', open);
        trigger?.setAttribute('aria-controls', `ui-drawer-${id}-content`);
    "
    x-on:click="
        openDrawer($event.target.closest('button, a, input, select, textarea, [tabindex]') ?? $el.firstElementChild)
    "
    data-slot="drawer-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
