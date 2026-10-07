@props(['value', 'showRemove' => true])

<span
    x-show="isSelected(@js($value))"
    data-slot="combobox-chip"
    {{ $attributes->twMerge(['class' => 'flex h-5.5 w-fit items-center justify-center gap-1 rounded-sm bg-muted px-1.5 text-xs font-medium whitespace-nowrap text-foreground']) }}
>
    {{ $slot }}
    @if ($showRemove)
        <button
            type="button"
            x-on:click.stop="removeValue(@js($value))"
            data-slot="combobox-chip-remove"
            class="-mr-1 inline-flex size-5 items-center justify-center rounded-sm opacity-50 hover:opacity-100"
        >
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
            <span class="sr-only">Remove</span>
        </button>
    @endif
</span>
