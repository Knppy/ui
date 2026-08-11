@props(['for' => null])

<span
    @if ($for)
        x-data
        @click="$dispatch('open-sheet-{{ $for }}')"
        aria-haspopup="dialog"
    @else
        @click="open = true"
        x-ui-trigger="{ haspopup: 'dialog', controls: $id('ui-sheet') }"
    @endif
    data-slot="sheet-trigger"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
