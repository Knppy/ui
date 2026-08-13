@props([])

<div
    data-slot="input-otp-group"
    role="group"
    {{ $attributes->twMerge('flex items-center') }}
>
    {{ $slot }}
</div>
