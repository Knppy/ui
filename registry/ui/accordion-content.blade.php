<div
    data-slot="accordion-content"
    role="region"
    :id="$id('ui-accordion-panel', _v)"
    :aria-labelledby="$id('ui-accordion-trigger', _v)"
    x-show="isOpen(_v)"
    x-collapse
    x-cloak
    :data-state="isOpen(_v) ? 'open' : 'closed'"
    class="overflow-hidden text-sm"
>
    <div {{ $attributes->twMerge('pt-0 pb-4') }}>{{ $slot }}</div>
</div>
