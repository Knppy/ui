@props([
    'placeholder' => null,
    'size' => 'default',
    'disabled' => false,
    'icon' => null,
])

@php
    $placeholder ??= __('Search...');

    $sizes = [
        'sm' => 'h-8 py-1 text-sm',
        'default' => 'h-9 py-2 text-sm',
        'lg' => 'h-10 py-2 text-base',
    ];
    $sizeCls = $sizes[$size] ?? $sizes['default'];
@endphp

<div class="relative">
    @if ($icon)
        <x-dynamic-component
            :component="'lucide-'.$icon"
            class="text-muted-foreground pointer-events-none absolute start-3 top-1/2 size-4 -translate-y-1/2"
            aria-hidden="true"
        />
    @endif
    <input
        x-ref="control"
        x-init="trigger = 'input'"
        x-model="query"
        type="text"
        role="combobox"
        aria-autocomplete="list"
        autocomplete="off"
        :aria-expanded="open"
        :aria-controls="$id('ui-combobox-list')"
        :aria-activedescendant="activeValue != null ? $id('ui-combobox-opt', activeValue) : null"
        @focus="openList()"
        @click="openList()"
        @input="onInput()"
        @keydown.down.prevent="move(1)"
        @keydown.up.prevent="move(-1)"
        @keydown.enter.prevent="selectActive()"
        @keydown.escape.prevent.stop="close()"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        data-slot="combobox-input"
        {{ $attributes->twMerge("border-input dark:bg-input/30 placeholder:text-muted-foreground flex w-full rounded-md border bg-transparent pe-9 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 $sizeCls ".($icon ? 'ps-9' : 'ps-3')) }}
    />
    <x-lucide-chevron-down
        class="text-muted-foreground pointer-events-none absolute end-3 top-1/2 size-4 -translate-y-1/2 opacity-50 transition-transform"
        ::class="open && 'rotate-180'"
        aria-hidden="true"
    />
</div>
