<span
    data-slot="collapsible-trigger"
    @click="open = ! open"
    :data-state="open ? 'open' : 'closed'"
    {{ $attributes->twMerge('inline-block') }}
>
    {{ $slot }}
</span>
