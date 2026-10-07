@props([
    'delay' => null,
    'closeDelay' => null,
])

<span
    x-init="registerTrigger($el.firstElementChild, @js($delay === null ? null : (int) $delay), @js($closeDelay === null ? null : (int) $closeDelay))"
    x-on:mouseenter="scheduleOpen()"
    x-on:mouseleave="scheduleClose()"
    x-on:focusin="scheduleOpen()"
    x-on:focusout="scheduleClose()"
    data-slot="hover-card-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
