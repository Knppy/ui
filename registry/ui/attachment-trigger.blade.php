@props([
    'as' => 'button',
])

<{{ $as }} data-slot="attachment-trigger" {{ $attributes->twMerge('absolute inset-0 z-10 outline-none') }}>
    {{ $slot }}
</{{ $as }}>
