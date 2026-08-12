@props([
    'href' => null,
    'isActive' => false,
    'size' => 'md',
])

@php
    $classes = [
        'flex h-7 min-w-0 -translate-x-px items-center gap-2 overflow-hidden rounded-md px-2 text-sidebar-foreground ring-sidebar-ring outline-none',
        'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
        'focus-visible:ring-2',
        'active:bg-sidebar-accent active:text-sidebar-accent-foreground',
        'disabled:pointer-events-none disabled:opacity-50',
        'aria-disabled:pointer-events-none aria-disabled:opacity-50',
        'data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground',
        '[&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0 [&>svg]:text-sidebar-accent-foreground',
        'group-data-[collapsible=icon]:hidden',
        $size === 'sm' ? 'text-xs' : 'text-sm',
    ];
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        data-slot="sidebar-menu-sub-button"
        data-sidebar="menu-sub-button"
        data-size="{{ $size }}"
        @if ($isActive) data-active="true" aria-current="page" @endif
        {{ $attributes->twMerge($classes) }}
    >{{ $slot }}</a>
@else
    <button
        type="button"
        data-slot="sidebar-menu-sub-button"
        data-sidebar="menu-sub-button"
        data-size="{{ $size }}"
        @if ($isActive) data-active="true" @endif
        {{ $attributes->twMerge($classes) }}
    >
        {{ $slot }}
    </button>
@endif
