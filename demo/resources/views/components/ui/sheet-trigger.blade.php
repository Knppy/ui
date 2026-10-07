<span
    x-init="registerTrigger($el.firstElementChild)"
    x-effect="
        trigger?.setAttribute('aria-expanded', open);
        trigger?.setAttribute('aria-controls', `ui-dialog-${id}-content`);
    "
    x-on:click="
        openDialog($event.target.closest('button, a, input, select, textarea, [tabindex]') ?? $el.firstElementChild)
    "
    data-slot="sheet-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
