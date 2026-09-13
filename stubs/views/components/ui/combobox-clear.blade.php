<button
    type="button"
    x-show="multiple ? value.length : value !== null && value !== ''"
    x-on:click="clear()"
    x-bind:disabled="disabled"
    data-slot="combobox-clear"
    {{ $attributes->twMerge(['class' => 'inline-flex size-6 items-center justify-center rounded-sm text-muted-foreground outline-none hover:bg-accent disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*=size-])]:size-4']) }}
>
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
        <path d="M18 6 6 18M6 6l12 12" />
    </svg>
    <span class="sr-only">Clear</span>
</button>
