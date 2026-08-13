@props([
    'placeholder' => null,
    'size' => 'default',
    'disabled' => false,
    'icon' => null,
])

@php
    $placeholder ??= __('Search...');

    $minH = ['sm' => 'min-h-8', 'default' => 'min-h-9', 'lg' => 'min-h-10'][$size] ?? 'min-h-9';
@endphp

<div
    x-ref="control"
    @click="! $refs.input.contains($event.target) && $refs.input.focus()"
    data-slot="combobox-chips"
    {{ $attributes->twMerge("border-input dark:bg-input/30 $minH flex w-full flex-wrap items-center gap-1 rounded-md border bg-transparent px-2 py-1 shadow-xs transition-[color,box-shadow] focus-within:border-ring focus-within:ring-ring/50 focus-within:ring-[3px] ".($disabled ? 'pointer-events-none opacity-50' : '')) }}
>
    @if ($icon)
        <x-dynamic-component
            :component="'lucide-'.$icon"
            class="text-muted-foreground pointer-events-none ms-1 size-4 shrink-0"
            aria-hidden="true"
        />
    @endif
    <template x-for="o in selected" :key="o.value">
        <span class="bg-secondary text-secondary-foreground inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-xs font-medium">
            <span x-text="o.label"></span>
            <span
                role="button"
                tabindex="-1"
                :aria-label="'Remove ' + o.label"
                @click.stop.prevent="remove(o.value)"
                class="hover:text-foreground/70 inline-flex cursor-pointer items-center rounded-sm outline-none"
            >
                <x-lucide-x class="size-3" aria-hidden="true" />
            </span>
        </span>
    </template>
    <input
        x-ref="input"
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
        @keydown.backspace="backspace()"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        data-slot="combobox-chips-input"
        class="placeholder:text-muted-foreground min-w-[6rem] flex-1 bg-transparent text-sm outline-none disabled:cursor-not-allowed"
    />
    <x-lucide-chevron-down
        class="text-muted-foreground pointer-events-none ms-auto size-4 shrink-0 self-center opacity-50 transition-transform"
        ::class="open && 'rotate-180'"
        aria-hidden="true"
    />
</div>
