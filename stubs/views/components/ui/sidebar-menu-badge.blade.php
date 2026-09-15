<div
    data-slot="sidebar-menu-badge"
    data-sidebar="menu-badge"
    {{ $attributes->twMerge(['class' => 'pointer-events-none absolute top-1.5 right-1 flex h-5 min-w-5 items-center justify-center rounded-md px-1 text-xs font-medium text-sidebar-foreground tabular-nums group-data-[collapsible=icon]:hidden']) }}
>
    {{ $slot }}
</div>
