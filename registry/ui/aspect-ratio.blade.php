@props([
    'ratio' => '16/9',
])

<div
    data-slot="aspect-ratio"
    style="aspect-ratio: {{ str_replace('/', ' / ', $ratio) }};"
    {{ $attributes->twMerge('relative overflow-hidden') }}
>
    {{ $slot }}
</div>
