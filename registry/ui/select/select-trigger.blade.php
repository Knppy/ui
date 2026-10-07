@props(['size' => 'default'])

<button
    type="button"
    role="combobox"
    x-init="registerTrigger($el)"
    x-on:click="toggleList()"
    x-on:keydown="handleKeydown($event)"
    x-bind:disabled="disabled"
    x-bind:aria-controls="contentId"
    x-bind:aria-expanded="open"
    x-bind:aria-activedescendant="open && selectableOptions[activeIndex] ? selectableOptions[activeIndex].id : null"
    x-bind:data-state="open ? 'open' : 'closed'"
    data-slot="select-trigger"
    data-size="{{ $size }}"
    {{ $attributes->twMerge(['class' => "flex w-fit items-center justify-between gap-2 rounded-md border border-input bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 data-[size=default]:h-9 data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 dark:bg-input/30 dark:hover:bg-input/50 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground"]) }}
>
    {{ $slot }}
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 opacity-50">
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>
