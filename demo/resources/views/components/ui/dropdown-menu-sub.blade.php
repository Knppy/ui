<div
    x-data="uiDropdownMenuSub"
    x-on:mouseenter="openSub()"
    x-on:mouseleave="scheduleSubClose()"
    data-slot="dropdown-menu-sub"
    {{ $attributes->twMerge(['class' => 'relative']) }}
>
    {{ $slot }}
</div>
