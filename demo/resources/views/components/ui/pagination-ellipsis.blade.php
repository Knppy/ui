<span
    aria-hidden="true"
    data-slot="pagination-ellipsis"
    {{ $attributes->twMerge(['class' => 'flex size-9 items-center justify-center']) }}
>
    <x-lucide-ellipsis class="size-4" />
    <span class="sr-only">{{ __('More pages') }}</span>
</span>
