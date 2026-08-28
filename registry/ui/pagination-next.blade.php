@props(['href' => '#'])

<x-ui.pagination-link
    :href="$href"
    aria-label="Go to next page"
    size="default"
    {{ $attributes->twMerge('gap-1 px-2.5 sm:pr-2.5') }}
>
    <span class="hidden sm:block">Next</span>
    <x-lucide-chevron-right />
</x-ui.pagination-link>
