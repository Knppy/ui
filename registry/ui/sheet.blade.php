@props(['open' => false, 'id' => null])

<div
    data-slot="sheet"
    x-data="{ open: @js((bool) $open) }"
    @if ($id)
        @open-sheet-{{ $id }}.window="open = true"
        @close-sheet-{{ $id }}.window="open = false"
    @endif
    x-id="['ui-sheet']"
    {{ $attributes }}
>
    {{ $slot }}
</div>
