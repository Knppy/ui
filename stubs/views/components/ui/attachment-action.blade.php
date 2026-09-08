@props([
    'variant' => 'ghost',
    'size' => 'icon-xs',
])

<x-ui.button dataSlot="attachment-action" :variant="$variant" :size="$size" {{ $attributes }}>{{ $slot }}</x-ui.button>
