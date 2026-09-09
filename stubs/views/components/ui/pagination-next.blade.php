<x-ui.pagination-link
    aria-label="Go to next page"
    size="default"
    {{ $attributes->twMerge(['class' => 'gap-1 px-2.5 sm:pr-2.5']) }}
>
    <span class="hidden sm:block">{{ __('Next') }}</span>
    <x-lucide-chevron-right />
</x-ui.pagination-link>
