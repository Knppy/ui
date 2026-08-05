<li
    data-slot="breadcrumb-separator"
    role="presentation"
    aria-hidden="true"
    {{ $attributes->twMerge('[&>svg]:size-3.5') }}
>
    @if ($slot->isEmpty())
        <x-lucide-chevron-right />
    @else
        {{ $slot }}
    @endif
</li>
