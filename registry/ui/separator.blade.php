@props([
    'orientation' => 'horizontal',
])

<div
    data-slot="separator"
    data-orientation="{{ $orientation }}"
    role="separator"
    {{ $attributes->twMerge('shrink-0 bg-border data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px') }}
>
    {{ $slot }}
</div>
