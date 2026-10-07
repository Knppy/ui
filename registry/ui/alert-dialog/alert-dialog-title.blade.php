<h2
    x-bind:id="titleId"
    data-slot="alert-dialog-title"
    {{ $attributes->twMerge(['class' => 'text-lg font-semibold sm:group-data-[size=default]/alert-dialog-content:group-has-data-[slot=alert-dialog-media]/alert-dialog-content:col-start-2']) }}
>
    {{ $slot }}
</h2>
