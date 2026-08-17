@props([
    'variant' => 'default',
    'size' => 'default',
])

<x-ui.button @click="open = false" data-slot="alert-dialog-action" :variant="$variant" :size="$size" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
