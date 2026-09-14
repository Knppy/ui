@props(['inset' => false])

<div
    data-slot="menubar-label"
    @if ($inset) data-inset @endif
    {{ $attributes->twMerge(['class' => 'px-2 py-1.5 text-sm font-medium '.($inset ? 'pl-8' : '')]) }}
>
    {{ $slot }}
</div>
