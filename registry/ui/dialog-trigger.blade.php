@props(['for' => null])

<span
    @if ($for)
        x-data
        @click.prevent="$dispatch('open-dialog-{{ $for }}')"
        aria-haspopup="dialog"
    @else
        @click.prevent="open = true"
        x-ui-trigger="{ haspopup: 'dialog', controls: $id('ui-dialog') }"
    @endif
    data-slot="dialog-trigger"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
