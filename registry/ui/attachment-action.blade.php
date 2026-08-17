@props([
    'variant' => 'ghost',
    'size' => 'icon-xs',
])

<x-ui.button data-slot="attachment-action" :variant="$variant" :size="$size" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
