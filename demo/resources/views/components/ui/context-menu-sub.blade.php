<div
    x-data="uiContextMenuSub"
    x-on:mouseenter="openSub()"
    x-on:mouseleave="scheduleSubClose()"
    data-slot="context-menu-sub"
    {{ $attributes->twMerge(['class' => 'relative']) }}
>
    {{ $slot }}
</div>
