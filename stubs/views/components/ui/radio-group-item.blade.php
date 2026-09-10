@props([
    'value',
    'name' => null,
])

<span class="relative inline-grid size-4 shrink-0 place-content-center">
    <input
        type="radio"
        value="{{ $value }}"
        @if ($name) name="{{ $name }}" @else x-bind:name="$root.dataset.name || null" @endif
        x-model="value"
        x-bind:data-state="value === @js($value) ? 'checked' : 'unchecked'"
        data-slot="radio-group-item"
        {{ $attributes->twMerge(['class' => 'peer aspect-square size-4 shrink-0 appearance-none rounded-full border border-input text-primary shadow-xs transition-[color,box-shadow] outline-none checked:border-primary checked:bg-primary focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:bg-input/30 dark:checked:bg-primary dark:aria-invalid:ring-destructive/40']) }}
    />
    <span
        aria-hidden="true"
        data-slot="radio-group-indicator"
        class="bg-primary-foreground pointer-events-none absolute inset-0 m-auto size-2 rounded-full opacity-0 peer-checked:opacity-100"
    ></span>
</span>
