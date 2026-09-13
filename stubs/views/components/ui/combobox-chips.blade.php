<div
    x-init="registerAnchor($el)"
    x-on:click="input?.focus()"
    data-slot="combobox-chips"
    {{ $attributes->twMerge(['class' => 'flex min-h-9 flex-wrap items-center gap-1.5 rounded-md border border-input bg-transparent px-2.5 py-1.5 text-sm shadow-xs transition-[color,box-shadow] focus-within:border-ring focus-within:ring-[3px] focus-within:ring-ring/50 dark:bg-input/30']) }}
>
    {{ $slot }}
</div>
