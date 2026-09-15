@props([
    'is' => 'a',
    'active' => false,
    'size' => 'md',
])

<{{ $is }}
    data-slot="sidebar-menu-sub-button"
    data-sidebar="menu-sub-button"
    data-size="{{ $size }}"
    data-active="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->twMerge(['class' => 'flex h-7 min-w-0 items-center gap-2 overflow-hidden rounded-md px-2 text-sidebar-foreground outline-none hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground data-[size=sm]:text-xs data-[size=md]:text-sm group-data-[collapsible=icon]:hidden [&>span:last-child]:truncate [&>svg]:size-4']) }}
>
    {{ $slot }}
</{{ $is }}>
