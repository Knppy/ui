@props(['value'])

<div
    x-data="{ accordionItem: @js($value) }"
    x-id="['accordion-trigger', 'accordion-content']"
    x-bind:data-state="isOpen(accordionItem) ? 'open' : 'closed'"
    data-slot="accordion-item"
    data-value="{{ $value }}"
    {{ $attributes->twMerge(['class' => 'border-b last:border-b-0']) }}
>
    {{ $slot }}
</div>
