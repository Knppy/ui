<button
    type="button"
    data-slot="sidebar-group-action"
    data-sidebar="group-action"
    {{ $attributes->twMerge(['class' => 'absolute top-3.5 right-3 flex aspect-square w-5 items-center justify-center rounded-md p-0 text-sidebar-foreground outline-none hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring group-data-[collapsible=icon]:hidden [&>svg]:size-4']) }}
>
    {{ $slot }}
</button>
