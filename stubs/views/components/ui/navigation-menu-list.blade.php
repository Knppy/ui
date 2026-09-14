<ul
    x-on:keydown="handleListKeydown($event)"
    data-slot="navigation-menu-list"
    {{ $attributes->twMerge(['class' => 'group flex flex-1 list-none items-center justify-center gap-1']) }}
>
    {{ $slot }}
</ul>
