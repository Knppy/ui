@props(['value' => null])

<li
    data-slot="navigation-menu-item"
    @if ($value !== null) data-value="{{ $value }}" @endif
    {{ $attributes->twMerge(['class' => 'relative']) }}
>
    {{ $slot }}
</li>
