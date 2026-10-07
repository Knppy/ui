<button
    type="button"
    tabindex="-1"
    aria-label="Toggle Sidebar"
    title="Toggle Sidebar"
    data-slot="sidebar-rail"
    data-sidebar="rail"
    x-on:click="toggleSidebar()"
    {{ $attributes->twMerge(['class' => 'absolute inset-y-0 z-20 hidden w-4 -translate-x-1/2 transition-all after:absolute after:inset-y-0 after:left-1/2 after:w-px hover:after:bg-sidebar-border group-data-[side=left]:-right-4 group-data-[side=right]:left-0 sm:flex']) }}
></button>
