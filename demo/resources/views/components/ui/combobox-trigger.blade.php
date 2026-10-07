<button
    type="button"
    role="combobox"
    x-init="if (! anchor) registerAnchor($el);"
    x-on:click="
        toggleList();
        input?.focus();
    "
    x-bind:disabled="disabled"
    x-bind:aria-controls="contentId"
    x-bind:aria-expanded="open"
    data-slot="combobox-trigger"
    {{ $attributes->twMerge(['class' => 'inline-flex size-6 items-center justify-center rounded-sm text-muted-foreground outline-none hover:bg-accent disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*=size-])]:size-4']) }}
>
    {{ $slot }}
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>
