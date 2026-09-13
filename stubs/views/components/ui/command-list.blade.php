<div
    role="listbox"
    data-slot="command-list"
    {{ $attributes->twMerge(['class' => 'max-h-[300px] scroll-py-1 overflow-x-hidden overflow-y-auto']) }}
>
    {{ $slot }}
</div>
