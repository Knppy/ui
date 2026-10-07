<h2
    x-bind:id="titleId"
    data-slot="sheet-title"
    {{ $attributes->twMerge(['class' => 'font-semibold text-foreground']) }}
>
    {{ $slot }}
</h2>
