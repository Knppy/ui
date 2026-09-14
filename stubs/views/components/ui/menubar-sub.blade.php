<div
    x-data="uiMenubarSub"
    x-effect="if (! isOpen(menu)) closeSub();"
    data-slot="menubar-sub"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</div>
