<h2
    x-bind:id="titleId"
    data-slot="dialog-title"
    {{ $attributes->twMerge(['class' => 'text-lg leading-none font-semibold']) }}
>
    {{ $slot }}
</h2>
