@props(['showIcon' => false])

<div
    data-slot="sidebar-menu-skeleton"
    data-sidebar="menu-skeleton"
    {{ $attributes->twMerge(['class' => 'flex h-8 items-center gap-2 rounded-md px-2']) }}
>
    @if ($showIcon)
        <div data-sidebar="menu-skeleton-icon" class="bg-muted size-4 animate-pulse rounded-md"></div>
    @endif
    <div data-sidebar="menu-skeleton-text" class="bg-muted h-4 w-2/3 max-w-full flex-1 animate-pulse rounded-md"></div>
</div>
