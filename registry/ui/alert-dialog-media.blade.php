<div
    data-slot="alert-dialog-media"
    {{ $attributes->twMerge('mb-2 inline-flex size-16 items-center justify-center rounded-md bg-muted sm:group-data-[size=default]/alert-dialog-content:row-span-2 [&_svg:not([class*=\'size-\'])]:size-8') }}
>
    {{ $slot }}
</div>
