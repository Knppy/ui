@props(['size' => 'default'])

<x-ui.alert-dialog-portal>
    <x-ui.alert-dialog-overlay />
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
        role="alertdialog"
        aria-modal="true"
        tabindex="-1"
        data-slot="alert-dialog-content"
        data-size="{{ $size }}"
        {{ $attributes->twMerge(['class' => 'group/alert-dialog-content fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg outline-none data-[size=sm]:max-w-xs data-[size=default]:sm:max-w-lg']) }}
    >
        {{ $slot }}
    </div>
</x-ui.alert-dialog-portal>
