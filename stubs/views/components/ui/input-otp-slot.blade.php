@props(['index'])

<div
    x-on:click="focusAt({{ (int) $index }})"
    x-bind:data-active="isActive({{ (int) $index }})"
    data-slot="input-otp-slot"
    {{ $attributes->twMerge(['class' => 'relative flex h-9 w-9 cursor-text items-center justify-center border-y border-r border-input text-sm shadow-xs transition-all outline-none first:rounded-l-md first:border-l last:rounded-r-md aria-invalid:border-destructive data-[active=true]:z-10 data-[active=true]:border-ring data-[active=true]:ring-[3px] data-[active=true]:ring-ring/50 data-[active=true]:aria-invalid:border-destructive data-[active=true]:aria-invalid:ring-destructive/20 dark:bg-input/30 dark:data-[active=true]:aria-invalid:ring-destructive/40']) }}
>
    <span x-text="character({{ (int) $index }})"></span>
    <span
        x-show="hasFakeCaret({{ (int) $index }})"
        aria-hidden="true"
        class="pointer-events-none absolute inset-0 flex items-center justify-center"
    >
        <span class="bg-foreground h-4 w-px animate-pulse"></span>
    </span>
</div>
