@props(['inset' => false, 'disabled' => false])

<div
    role="menuitem"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    x-init="registerSubTrigger($el)"
    x-on:click="if (! @js($disabled)) openSub(true)"
    x-on:keydown.arrow-right.prevent.stop="if (! @js($disabled)) openSub(true)"
    x-bind:aria-expanded="open"
    x-bind:data-state="open ? 'open' : 'closed'"
    aria-haspopup="menu"
    data-slot="context-menu-sub-trigger"
    @if ($inset) data-inset @endif
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50"]) }}
>
    {{ $slot }}
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto size-4">
        <path d="m9 18 6-6-6-6" />
    </svg>
</div>
