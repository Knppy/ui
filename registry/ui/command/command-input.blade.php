<div data-slot="command-input-wrapper" class="flex h-9 items-center gap-2 border-b px-3">
    <svg class="size-4 shrink-0 opacity-50" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.3-4.3" />
    </svg>
    <input
        type="text"
        role="combobox"
        aria-autocomplete="list"
        x-model="query"
        x-on:input="filter($event.currentTarget.value)"
        x-on:keydown="handleKeydown($event)"
        x-bind:aria-expanded="visibleItems.length > 0"
        data-slot="command-input"
        {{ $attributes->twMerge(['class' => 'flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-hidden placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50']) }}
    />
</div>
