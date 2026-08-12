@props(['showIcon' => false])

<div
    data-slot="sidebar-menu-skeleton"
    data-sidebar="menu-skeleton"
    {{ $attributes->twMerge('flex h-8 items-center gap-2 rounded-md px-2') }}
>
    @if ($showIcon)
        <div data-sidebar="menu-skeleton-icon" class="bg-accent size-4 shrink-0 animate-pulse rounded-md"></div>
    @endif
    <div
        data-sidebar="menu-skeleton-text"
        class="bg-accent h-4 max-w-[--skeleton-width] flex-1 animate-pulse rounded-md"
        style="--skeleton-width: {{ rand(50, 90) }}%"
    ></div>
</div>
