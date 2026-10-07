<div
    x-cloak
    x-show="isOpen(accordionItem)"
    x-collapse
    role="region"
    x-bind:id="$id('accordion-content')"
    x-bind:aria-labelledby="$id('accordion-trigger')"
    x-bind:data-state="isOpen(accordionItem) ? 'open' : 'closed'"
    data-slot="accordion-content"
    class="overflow-hidden text-sm"
>
    <div {{ $attributes->twMerge(['class' => 'pt-0 pb-4']) }}>{{ $slot }}</div>
</div>
