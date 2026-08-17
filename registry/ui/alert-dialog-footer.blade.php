<div
    data-slot="alert-dialog-footer"
    {{ $attributes->twMerge('flex flex-col-reverse gap-2 group-data-[size=sm]/alert-dialog-content:grid group-data-[size=sm]/alert-dialog-content:grid-cols-2 sm:flex-row sm:justify-end') }}
>
    {{ $slot }}
</div>
