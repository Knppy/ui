@props([
    'href' => null,
    'isActive' => false,
    'variant' => 'default',
    'size' => 'default',
    'tooltip' => null,
])

@php
    $base = 'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start text-sm outline-none ring-sidebar-ring transition-[width,height,padding] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 group-has-[[data-sidebar=menu-action]]/menu-item:pe-8 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground data-[state=open]:hover:bg-sidebar-accent data-[state=open]:hover:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8! [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0';
    $variants = [
        'default' => 'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
        'outline' => 'bg-background shadow-[0_0_0_1px_var(--sidebar-border)] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground hover:shadow-[0_0_0_1px_var(--sidebar-accent)]',
    ];
    $sizes = [
        'default' => 'h-8 text-sm',
        'sm' => 'h-7 text-xs',
        'lg' => 'h-12 text-sm',
    ];

    $iconPad = $size === 'lg' ? 'group-data-[collapsible=icon]:p-0!' : 'group-data-[collapsible=icon]:p-2!';
    $classes = $base.' '.($variants[$variant] ?? $variants['default']).' '.($sizes[$size] ?? $sizes['default']).' '.$iconPad;
@endphp

@if ($tooltip)
    <div
        data-slot="sidebar-menu-button-tooltip"
        x-data="{ tipOpen: false }"
        x-id="['ui-tooltip']"
        @mouseenter="tipOpen = true"
        @mouseleave="tipOpen = false"
        @focusin="tipOpen = true"
        @focusout="tipOpen = false"
        class="block w-full"
    >
@endif

@if ($href)
    <a
        href="{{ $href }}"
        data-slot="sidebar-menu-button"
        data-sidebar="menu-button"
        data-size="{{ $size }}"
        @if ($isActive) data-active="true" aria-current="page" @endif
        @if ($tooltip) x-ref="trigger" :aria-describedby="tipOpen && collapsed ? $id('ui-tooltip') : null" @endif
        {{ $attributes->twMerge($classes) }}
    >{{ $slot }}</a>
@else
    <button
        type="button"
        data-slot="sidebar-menu-button"
        data-sidebar="menu-button"
        data-size="{{ $size }}"
        @if ($isActive) data-active="true" @endif
        @if ($tooltip) x-ref="trigger" :aria-describedby="tipOpen && collapsed ? $id('ui-tooltip') : null" @endif
        {{ $attributes->twMerge($classes) }}
    >
        {{ $slot }}
    </button>
@endif

@if ($tooltip)
    <div
        x-show="tipOpen && collapsed"
        x-cloak
        :id="$id('ui-tooltip')"
        role="tooltip"
        class="bg-sidebar-foreground text-sidebar absolute top-1/2 left-full z-50 ms-2 -translate-y-1/2 rounded-md px-2 py-1 text-xs shadow-md"
    >
        {{ $tooltip }}
    </div>
    </div>
@endif
