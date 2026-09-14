@props([
    'dir' => null,
    'direction' => null,
])

<div dir="{{ $direction ?? $dir ?? 'ltr' }}" data-slot="direction-provider" {{ $attributes }}>{{ $slot }}</div>
