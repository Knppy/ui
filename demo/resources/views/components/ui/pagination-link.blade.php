@props([
    'active' => false,
    'size' => 'icon',
])

<x-ui.button
    is="a"
    dataSlot="pagination-link"
    data-active="{{ $active ? 'true' : 'false' }}"
    :aria-current="$active ? 'page' : null"
    :variant="$active ? 'outline' : 'ghost'"
    :size="$size"
    {{ $attributes }}
>
    {{ $slot }}</x-ui.button>
