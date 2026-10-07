<div
    x-cloak
    x-show="open"
    x-transition.opacity
    x-bind:data-state="open ? 'open' : 'closed'"
    x-on:click.self="closeDialog()"
    data-slot="dialog-overlay"
    {{ $attributes->twMerge(['class' => 'fixed inset-0 z-50 bg-black/50']) }}
></div>
