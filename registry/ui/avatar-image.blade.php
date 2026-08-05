@props([
    'src' => '',
    'alt' => '',
])

@if ($src)
    <img
        data-slot="avatar-image"
        src="{{ $src }}"
        alt="{{ $alt }}"
        {{ $attributes->twMerge('aspect-square size-full') }}
    />
@endif
