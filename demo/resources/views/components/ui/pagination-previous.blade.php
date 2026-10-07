<x-ui.pagination-link
    aria-label="Go to previous page"
    size="default"
    {{ $attributes->twMerge(['class' => 'gap-1 px-2.5 sm:pl-2.5']) }}
>
    <x-lucide-chevron-left />
    <span class="hidden sm:block">{{ __('Previous') }}</span>
</x-ui.pagination-link>
