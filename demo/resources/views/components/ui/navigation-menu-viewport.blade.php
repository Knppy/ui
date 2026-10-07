<div
    x-cloak
    x-show="value !== null"
    x-init="registerViewport($el)"
    x-bind:data-state="value !== null ? 'open' : 'closed'"
    data-slot="navigation-menu-viewport"
    {{ $attributes->twMerge(['class' => 'absolute top-full left-0 isolate z-50 mt-1.5 min-w-48 overflow-hidden rounded-md border bg-popover text-popover-foreground shadow transition-[left,width,height] duration-200 ease-out']) }}
></div>
