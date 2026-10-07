@props(['align' => 'center', 'side' => 'top', 'sideOffset' => 0])

@php($position = $side.($align === 'center' ? '' : '-'.$align))

<template x-teleport="body">
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-anchor.{{ $position }}.offset.{{ $sideOffset }}="trigger"
        x-bind:id="contentId"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="tooltip"
        data-side="{{ $side }}"
        data-align="{{ $align }}"
        data-slot="tooltip-content"
        @class([
            'relative z-50 w-fit rounded-md bg-foreground px-3 py-1.5 text-xs text-balance text-background shadow-md',
            '-translate-y-2' => $side === 'top',
            'translate-y-2' => $side === 'bottom',
            '-translate-x-2' => $side === 'left',
            'translate-x-2' => $side === 'right',
            $attributes->get('class'),
        ])
        {{ $attributes->except('class') }}
    >
        {{ $slot }}
        <span
            aria-hidden="true"
            @class([
                'absolute size-2.5 rotate-45 rounded-[2px] bg-foreground',
                'top-full left-1/2 -translate-x-1/2 -translate-y-1/2' => $side === 'top',
                'bottom-full left-1/2 -translate-x-1/2 translate-y-1/2' => $side === 'bottom',
                'top-1/2 left-full -translate-x-1/2 -translate-y-1/2' => $side === 'left',
                'top-1/2 right-full translate-x-1/2 -translate-y-1/2' => $side === 'right',
            ])
        ></span>
    </div>
</template>
