@props(['for' => null])

<span
    @if ($for)
        x-data
        @click.prevent="$dispatch('open-alert-dialog-{{ $for }}')"
        aria-haspopup="dialog"
    @else
        @click.prevent="open = true"
        x-ui-trigger="{ haspopup: 'dialog', controls: $id('ui-alert-dialog') }"
    @endif
    data-slot="alert-dialog-trigger"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
