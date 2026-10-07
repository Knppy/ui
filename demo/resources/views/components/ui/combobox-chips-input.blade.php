<input
    role="combobox"
    autocomplete="off"
    x-init="registerInput($el)"
    x-model="query"
    x-on:input="filter()"
    x-on:focus="handleFocus()"
    x-on:click="if (! open) openList();"
    x-on:keydown="handleKeydown($event)"
    x-bind:disabled="disabled"
    x-bind:aria-controls="contentId"
    x-bind:aria-expanded="open"
    x-bind:aria-activedescendant="open && visibleOptions[activeIndex] ? visibleOptions[activeIndex].id : null"
    data-slot="combobox-chip-input"
    {{ $attributes->twMerge(['class' => 'min-w-16 flex-1 bg-transparent outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed']) }}
/>
