@props(['showOnHover' => false])

<button
    type="button"
    data-slot="sidebar-menu-action"
    data-sidebar="menu-action"
    @if ($showOnHover) data-show-on-hover @endif
    {{ $attributes->twMerge(['class' => 'absolute top-1.5 right-1 flex aspect-square w-5 items-center justify-center rounded-md p-0 text-sidebar-foreground outline-none hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring group-data-[collapsible=icon]:hidden data-[show-on-hover]:opacity-0 data-[show-on-hover]:group-focus-within/menu-item:opacity-100 data-[show-on-hover]:group-hover/menu-item:opacity-100 [&>svg]:size-4']) }}
>
    {{ $slot }}
</button>
