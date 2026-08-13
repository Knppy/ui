@props([
    'placeholder' => null,
    'width' => 'w-[200px]',
    'size' => 'default',
    'disabled' => false,
])

@php
    $placeholder ??= __('Select option...');

    $sizes = [
        'sm' => 'h-8',
        'default' => 'h-9',
        'lg' => 'h-10',
    ];
    $sizeCls = $sizes[$size] ?? $sizes['default'];
@endphp

<button
    type="button"
    x-ref="trigger"
    @click="toggle()"
    @keydown.down.prevent.stop="openList()"
    @keydown.up.prevent.stop="openList()"
    @keydown.enter.prevent.stop="openList()"
    @keydown.space.prevent.stop="openList()"
    role="combobox"
    aria-haspopup="listbox"
    aria-label="{{ $placeholder }}"
    :aria-expanded="open"
    :aria-controls="$id('ui-combobox-list')"
    @disabled($disabled)
    data-slot="combobox-trigger"
    {{ $attributes->twMerge("$width $sizeCls inline-flex items-center justify-between gap-2 rounded-md border border-input bg-transparent px-3 py-1.5 text-sm font-normal whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none hover:bg-transparent focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 dark:bg-input/30 dark:hover:bg-input/50") }}
>
    {{ $slot }}
    <x-lucide-chevrons-up-down class="size-4 shrink-0 self-center opacity-50" aria-hidden="true" />
</button>
