<span
    x-on:click="toggle"
    x-effect="{!! '$el.firstElementChild?.setAttribute(\'aria-expanded\', open.toString())' !!}"
    x-bind:data-state="open ? 'open' : 'closed'"
    data-slot="collapsible-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
