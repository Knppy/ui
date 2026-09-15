<ul
    data-slot="sidebar-menu"
    data-sidebar="menu"
    {{ $attributes->twMerge(['class' => 'flex w-full min-w-0 flex-col gap-1']) }}
>
    {{ $slot }}
</ul>
