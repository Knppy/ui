@props(['orientation' => 'horizontal'])

<div
    x-data="uiResizable(@js($orientation))"
    x-init="initialize($el)"
    data-slot="resizable-panel-group"
    data-orientation="{{ $orientation }}"
    {{ $attributes->twMerge(['class' => 'flex h-full w-full data-[orientation=vertical]:flex-col']) }}
>
    {{ $slot }}
</div>
