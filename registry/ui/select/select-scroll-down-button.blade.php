<button
    type="button"
    tabindex="-1"
    x-on:click="scroll(1)"
    data-slot="select-scroll-down-button"
    {{ $attributes->twMerge(['class' => 'sticky bottom-0 z-10 flex w-full cursor-default items-center justify-center bg-popover py-1']) }}
>
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
        <path d="m6 9 6 6 6-6" />
    </svg>
    <span class="sr-only">Scroll down</span>
</button>
