<h2
    x-bind:id="titleId"
    data-slot="drawer-title"
    {{ $attributes->twMerge(['class' => 'font-semibold text-foreground']) }}
>
    {{ $slot }}
</h2>
