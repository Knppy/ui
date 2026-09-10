@props([
    'side' => 'right',
    'showCloseButton' => true,
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('fixed z-50 flex flex-col gap-4 bg-background shadow-lg outline-none transition ease-in-out')
        ->add(match ($side) {
            'top' => 'inset-x-0 top-0 h-auto border-b',
            'bottom' => 'inset-x-0 bottom-0 h-auto border-t',
            'left' => 'inset-y-0 left-0 h-full w-3/4 border-r sm:max-w-sm',
            default => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm',
        });

    $closedClass = match ($side) {
        'top' => '-translate-y-full',
        'bottom' => 'translate-y-full',
        'left' => '-translate-x-full',
        default => 'translate-x-full',
    };
@endphp

<x-ui.sheet-portal>
    <x-ui.sheet-overlay />
    <div
        x-cloak
        x-show="open"
        x-transition:enter="duration-500"
        x-transition:enter-start="{{ $closedClass }}"
        x-transition:enter-end="translate-x-0 translate-y-0"
        x-transition:leave="duration-300"
        x-transition:leave-start="translate-x-0 translate-y-0"
        x-transition:leave-end="{{ $closedClass }}"
        x-ref="content"
        x-trap.inert.noscroll="open"
        x-bind:id="`ui-dialog-${id}-content`"
        x-bind:aria-labelledby="titleId"
        x-bind:aria-describedby="descriptionId"
        x-bind:data-state="open ? 'open' : 'closed'"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        data-slot="sheet-content"
        data-side="{{ $side }}"
        {{ $attributes->twMerge(['class' => $classes]) }}
    >
        {{ $slot }}

        @if ($showCloseButton)
            <button
                type="button"
                x-on:click="closeDialog()"
                data-slot="sheet-close"
                class="ring-offset-background focus:ring-ring [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 data-[state=open]:bg-secondary absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12" /></svg>
                <span class="sr-only">Close</span>
            </button>
        @endif
    </div>
</x-ui.sheet-portal>
