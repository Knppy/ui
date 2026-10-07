<div
    x-show="visibleCount === 0"
    role="status"
    data-slot="command-empty"
    {{ $attributes->twMerge(['class' => 'py-6 text-center text-sm']) }}
>
    {{ $slot }}
</div>
