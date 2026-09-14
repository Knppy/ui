<div
    x-cloak
    x-show="open"
    x-transition.opacity
    x-bind:data-state="open ? 'open' : 'closed'"
    x-on:click.self="if (dismissible) closeDrawer();"
    data-slot="drawer-overlay"
    {{ $attributes->twMerge(['class' => 'fixed inset-0 z-50 bg-black/50']) }}
></div>
