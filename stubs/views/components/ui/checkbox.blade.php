@props(['checked' => false])

<span
    x-data="uiCheckbox(@js($checked))"
    x-modelable="open"
    x-bind:data-state="open ? 'checked' : 'unchecked'"
    data-slot="checkbox-wrapper"
    {{ $attributes->whereStartsWith('x-model') }}
    class="relative inline-grid size-4 shrink-0 place-content-center"
>
    <input
        type="checkbox"
        @checked($checked)
        x-model="open"
        x-bind:data-state="open ? 'checked' : 'unchecked'"
        data-slot="checkbox"
        {{ $attributes->whereDoesntStartWith('x-model')->twMerge(['class' => 'peer size-4 shrink-0 appearance-none rounded-[4px] border border-input shadow-xs transition-shadow outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 checked:border-primary checked:bg-primary dark:bg-input/30 dark:aria-invalid:ring-destructive/40 dark:checked:bg-primary']) }}
    />
    <svg
        aria-hidden="true"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="3"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="text-primary-foreground pointer-events-none absolute inset-0 m-auto size-3.5 opacity-0 peer-checked:opacity-100"
    >
        <path d="m5 12 4 4L19 6" />
    </svg>
</span>
