@props(['showCloseButton' => true])

<x-ui.dialog-portal>
    <x-ui.dialog-overlay />
    <div
        x-cloak
        x-show="open"
        x-transition
        x-ref="content"
        x-trap.inert.noscroll="open"
        x-bind:id="`ui-dialog-${id}-content`"
        x-bind:aria-labelledby="titleId"
        x-bind:aria-describedby="descriptionId"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        data-slot="dialog-content"
        {{ $attributes->twMerge(['class' => 'fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg outline-none sm:max-w-lg']) }}
    >
        {{ $slot }}

        @if ($showCloseButton)
            <button
                type="button"
                x-on:click="closeDialog()"
                data-slot="dialog-close"
                class="ring-offset-background focus:ring-ring [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12" /></svg>
                <span class="sr-only">Close</span>
            </button>
        @endif
    </div>
</x-ui.dialog-portal>
