@props(['inset' => false, 'disabled' => false])

<div
    x-init="registerSubTrigger($el)"
    x-on:mouseenter="if (! @js($disabled)) openSub()"
    x-on:mouseleave="scheduleSubClose()"
    x-on:click.stop="if (! @js($disabled)) openSub(true)"
    x-on:keydown.arrow-right.prevent.stop="openSub(true)"
    x-bind:aria-expanded="open"
    x-bind:data-state="open ? 'open' : 'closed'"
    role="menuitem"
    aria-haspopup="menu"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    data-slot="menubar-sub-trigger"
    @if ($inset) data-inset @endif
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => 'flex cursor-default items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 '.($inset ? 'pl-8' : '')]) }}
>
    {{ $slot }}
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto size-4"><path d="m9 18 6-6-6-6" /></svg>
</div>
