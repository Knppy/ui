@props(['size' => 'default'])

<template x-teleport="body">
    <div x-show="open" x-cloak class="fixed inset-0 z-50">
        <div
            x-show="open"
            role="presentation"
            aria-hidden="true"
            data-slot="alert-dialog-overlay"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/50"
        ></div>

        <div
            x-show="open"
            x-trap.noscroll.inert="open"
            :id="$id('ui-alert-dialog')"
            x-ui-labelledby="{ label: '[data-slot=alert-dialog-title]', description: '[data-slot=alert-dialog-description]' }"
            role="alertdialog"
            aria-modal="true"
            tabindex="-1"
            data-slot="alert-dialog-content"
            data-size="{{ $size }}"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            {{ $attributes->twMerge('group/alert-dialog-content bg-background fixed top-1/2 left-1/2 z-50 grid w-full max-w-[calc(100%-2rem)] -translate-x-1/2 -translate-y-1/2 gap-4 rounded-lg border p-6 shadow-lg data-[size=sm]:max-w-xs data-[size=default]:sm:max-w-lg') }}
        >
            {{ $slot }}
        </div>
    </div>
</template>
