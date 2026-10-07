<div
    x-cloak
    x-show="value !== null"
    x-init="registerIndicator($el)"
    x-bind:data-state="value !== null ? 'visible' : 'hidden'"
    data-slot="navigation-menu-indicator"
    {{ $attributes->twMerge(['class' => 'absolute top-full z-[51] flex h-1.5 items-end justify-center overflow-hidden']) }}
>
    <div class="bg-border relative top-[60%] size-2 rotate-45 rounded-tl-sm shadow-md"></div>
</div>
