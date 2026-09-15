<div
    data-slot="sidebar-group-label"
    data-sidebar="group-label"
    {{ $attributes->twMerge(['class' => 'flex h-8 shrink-0 items-center rounded-md px-2 text-xs font-medium text-sidebar-foreground/70 outline-none transition-[margin,opacity] duration-200 group-data-[collapsible=icon]:-mt-8 group-data-[collapsible=icon]:opacity-0 [&>svg]:size-4 [&>svg]:shrink-0']) }}
>
    {{ $slot }}
</div>
