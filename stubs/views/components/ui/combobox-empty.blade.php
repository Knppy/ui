<div
    x-show="visibleOptions.length === 0"
    role="status"
    data-slot="combobox-empty"
    {{ $attributes->twMerge(['class' => 'flex w-full justify-center py-2 text-center text-sm text-muted-foreground']) }}
>
    {{ $slot }}
</div>
