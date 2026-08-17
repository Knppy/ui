@props([
    'variant' => 'outline',
    'size' => 'default',
])

<x-ui.button
    @click="open = false"
    data-slot="alert-dialog-cancel"
    :variant="$variant"
    :size="$size"
    {{ $attributes }}
>
    {{ $slot }}
</x-ui.button>
