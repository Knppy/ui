<p
    x-bind:id="descriptionId"
    data-slot="dialog-description"
    {{ $attributes->twMerge(['class' => 'text-sm text-muted-foreground']) }}
>
    {{ $slot }}
</p>
