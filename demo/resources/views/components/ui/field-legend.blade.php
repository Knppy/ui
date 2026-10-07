@props(['variant' => 'legend'])

<legend
    data-slot="field-legend"
    data-variant="{{ $variant }}"
    {{ $attributes->twMerge(['class' => 'mb-3 font-medium data-[variant=legend]:text-base data-[variant=label]:text-sm']) }}
>
    {{ $slot }}
</legend>
