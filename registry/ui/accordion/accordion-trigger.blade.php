<h3 class="flex">
    <button
        type="button"
        x-on:click="toggle(accordionItem)"
        x-bind:id="$id('accordion-trigger')"
        x-bind:aria-controls="$id('accordion-content')"
        x-bind:aria-expanded="isOpen(accordionItem)"
        x-bind:data-state="isOpen(accordionItem) ? 'open' : 'closed'"
        data-slot="accordion-trigger"
        {{ $attributes->twMerge(['class' => 'flex flex-1 items-start justify-between gap-4 rounded-md py-4 text-left text-sm font-medium transition-all outline-none hover:underline focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 [&[data-state=open]>svg]:rotate-180']) }}
    >
        {{ $slot }}
        <svg
            aria-hidden="true"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="text-muted-foreground pointer-events-none size-4 shrink-0 translate-y-0.5 transition-transform duration-200"
        >
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>
</h3>
