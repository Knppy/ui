@props(['placeholder' => null])

<span data-slot="combobox-value" {{ $attributes }}>
    @if ($slot->isEmpty())
        <span x-text="selectedLabel || @js($placeholder)"></span>
    @else
        {{ $slot }}
    @endif
</span>
