@props([
    'inset' => false,
])

<div
    data-slot="context-menu-label"
    @if ($inset) data-inset="true" @endif
    {{ $attributes->twMerge('px-2 py-1.5 text-sm font-medium text-foreground data-[inset]:pl-8') }}
>
    {{ $slot }}
</div>
