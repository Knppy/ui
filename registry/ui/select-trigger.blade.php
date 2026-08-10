@props(['size' => 'default', 'ariaLabel' => null])

<button
    type="button"
    x-ref="trigger"
    x-init="_trigger = $el"
    @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    @click="toggleList()"
    @keydown.down.prevent.stop="openList()"
    @keydown.up.prevent.stop="openList()"
    @keydown.enter.prevent.stop="openList()"
    @keydown.space.prevent.stop="openList()"
    role="combobox"
    aria-haspopup="listbox"
    :aria-controls="$id('ui-listbox')"
    :aria-expanded="open"
    :data-state="open ? 'open' : 'closed'"
    :data-placeholder="label ? null : true"
    data-slot="select-trigger"
    data-size="{{ $size }}"
    {{ $attributes->twMerge("flex w-fit items-center justify-between gap-2 rounded-md border border-input bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 data-[placeholder]:text-muted-foreground data-[size=default]:h-9 data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 dark:bg-input/30 dark:hover:bg-input/50 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground") }}
>
    {{ $slot }}
    <x-lucide-chevron-down class="size-4 opacity-50" aria-hidden="true" />
</button>
