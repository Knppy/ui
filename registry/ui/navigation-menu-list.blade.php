@props([])

<ul
    data-slot="navigation-menu-list"
    x-init="registerList($el)"
    {{ $attributes->twMerge('group flex flex-1 list-none items-center justify-center gap-1') }}
>
    {{ $slot }}
</ul>
