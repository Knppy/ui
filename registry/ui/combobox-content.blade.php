@props([
    'searchable' => true,
    'searchPlaceholder' => null,
])

@php
    $searchPlaceholder ??= __('Search...');
@endphp

<template x-teleport="body" wire:ignore>
    <div
        x-ui-dialog-layer
        x-show="open"
        x-cloak
        data-slot="combobox-content"
        x-ui-anchor.bottom-start.offset.4.match-width="$refs.control ?? $refs.trigger"
        @click.outside="open && ! ($refs.control ?? $refs.trigger)?.contains($event.target) && close()"
        @keydown.escape.prevent.stop="close()"
        class="bg-popover text-popover-foreground z-50 flex w-fit origin-top flex-col overflow-hidden rounded-md border p-0 shadow-md"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes }}
    >
        <div class="flex h-full min-h-0 w-full flex-col overflow-hidden rounded-md">
            @if ($searchable)
                <div class="flex h-9 shrink-0 items-center gap-2 border-b px-3">
                    <x-lucide-search class="size-4 shrink-0 opacity-50" aria-hidden="true" />
                    <input
                        x-ref="search"
                        x-model="query"
                        type="text"
                        role="combobox"
                        aria-expanded="true"
                        aria-autocomplete="list"
                        autocomplete="off"
                        aria-label="{{ $searchPlaceholder }}"
                        :aria-controls="$id('ui-combobox-list')"
                        :aria-activedescendant="activeValue != null ? $id('ui-combobox-opt', activeValue) : null"
                        @keydown.down.prevent="move(1)"
                        @keydown.up.prevent="move(-1)"
                        @keydown.home.prevent="edge('first')"
                        @keydown.end.prevent="edge('last')"
                        @keydown.enter.prevent="selectActive()"
                        placeholder="{{ $searchPlaceholder }}"
                        data-slot="combobox-search"
                        class="placeholder:text-muted-foreground flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-hidden"
                    />
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
</template>
