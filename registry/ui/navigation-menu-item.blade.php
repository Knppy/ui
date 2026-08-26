@props([
    'value' => null,
])

@php
    // Generate a stable value when none is provided. Since this runs server-side
    // each render gets a unique ID — stable across re-renders via Livewire because
    // Blade components are re-rendered, not diffed.
    $itemValue = $value ?? 'nav-item-'.\Illuminate\Support\Str::random(8);
@endphp

<li
    data-slot="navigation-menu-item"
    x-data="{ _itemValue: @js($itemValue) }"
    @mouseleave="close()"
    {{ $attributes->twMerge('relative') }}
>
    {{ $slot }}
</li>
