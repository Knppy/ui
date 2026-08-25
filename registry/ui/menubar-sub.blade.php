<div
    data-slot="menubar-sub"
    x-data="{ subOpen: false, _subCloseTimer: null }"
    @mouseenter="
        clearTimeout(_subCloseTimer);
        subOpen = true;
    "
    @mouseleave="_subCloseTimer = setTimeout(() => (subOpen = false), 100)"
    {{ $attributes->twMerge('relative') }}
>
    {{ $slot }}
</div>
