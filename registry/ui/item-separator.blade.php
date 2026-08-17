@props([
    'orientation' => 'horizontal',
])

<div
    data-slot="item-separator"
    data-orientation="{{ $orientation }}"
    role="separator"
    {{ $attributes->twMerge('my-0 shrink-0 bg-border data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px') }}
></div>
