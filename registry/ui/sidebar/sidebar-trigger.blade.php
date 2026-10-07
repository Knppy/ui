<button
    type="button"
    data-slot="sidebar-trigger"
    data-sidebar="trigger"
    aria-label="Toggle Sidebar"
    x-on:click="toggleSidebar()"
    {{ $attributes->twMerge(['class' => 'inline-flex size-7 items-center justify-center rounded-md text-sm outline-none hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring']) }}
>
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
        <rect width="18" height="18" x="3" y="3" rx="2" />
        <path d="M9 3v18" />
    </svg>
    <span class="sr-only">Toggle Sidebar</span>
</button>
