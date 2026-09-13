<div
    x-show="visibleOptions.length > 0"
    role="listbox"
    data-slot="combobox-list"
    {{ $attributes->twMerge(['class' => 'max-h-80 scroll-py-1 overflow-y-auto p-1']) }}
>
    {{ $slot }}
</div>
