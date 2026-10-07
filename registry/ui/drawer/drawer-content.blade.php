<x-ui.drawer-portal>
    <x-ui.drawer-overlay />
    <div
        x-cloak
        x-show="open"
        x-transition
        x-ref="content"
        x-trap.inert.noscroll="open"
        x-bind:id="`ui-drawer-${id}-content`"
        x-bind:aria-labelledby="titleId"
        x-bind:aria-describedby="descriptionId"
        x-bind:data-state="open ? 'open' : 'closed'"
        x-bind:data-vaul-drawer-direction="direction"
        x-on:pointerdown="startDrag($event)"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        data-slot="drawer-content"
        {{ $attributes->twMerge(['class' => 'group/drawer-content fixed z-50 flex h-auto flex-col bg-background outline-none transition-transform duration-300 data-[state=closed]:duration-200 data-[vaul-drawer-direction=top]:inset-x-0 data-[vaul-drawer-direction=top]:top-0 data-[vaul-drawer-direction=top]:mb-24 data-[vaul-drawer-direction=top]:max-h-[80vh] data-[vaul-drawer-direction=top]:rounded-b-lg data-[vaul-drawer-direction=top]:border-b data-[vaul-drawer-direction=top]:data-[state=closed]:-translate-y-full data-[vaul-drawer-direction=bottom]:inset-x-0 data-[vaul-drawer-direction=bottom]:bottom-0 data-[vaul-drawer-direction=bottom]:mt-24 data-[vaul-drawer-direction=bottom]:max-h-[80vh] data-[vaul-drawer-direction=bottom]:rounded-t-lg data-[vaul-drawer-direction=bottom]:border-t data-[vaul-drawer-direction=bottom]:data-[state=closed]:translate-y-full data-[vaul-drawer-direction=right]:inset-y-0 data-[vaul-drawer-direction=right]:right-0 data-[vaul-drawer-direction=right]:w-3/4 data-[vaul-drawer-direction=right]:border-l data-[vaul-drawer-direction=right]:data-[state=closed]:translate-x-full data-[vaul-drawer-direction=right]:sm:max-w-sm data-[vaul-drawer-direction=left]:inset-y-0 data-[vaul-drawer-direction=left]:left-0 data-[vaul-drawer-direction=left]:w-3/4 data-[vaul-drawer-direction=left]:border-r data-[vaul-drawer-direction=left]:data-[state=closed]:-translate-x-full data-[vaul-drawer-direction=left]:sm:max-w-sm']) }}
    >
        <div class="bg-muted mx-auto mt-4 hidden h-2 w-[100px] shrink-0 rounded-full group-data-[vaul-drawer-direction=bottom]/drawer-content:block"></div>
        {{ $slot }}
    </div>
</x-ui.drawer-portal>
