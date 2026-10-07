@props([
    'showTrigger' => true,
    'showClear' => false,
    'disabled' => false,
])

<div
    x-init="registerAnchor($el)"
    data-slot="input-group"
    {{ $attributes->only('class')->twMerge(['class' => 'group/input-group relative flex h-9 w-auto min-w-0 items-center rounded-md border border-input shadow-xs transition-[color,box-shadow] outline-none has-[[data-slot=input-group-control]:focus-visible]:border-ring has-[[data-slot=input-group-control]:focus-visible]:ring-[3px] has-[[data-slot=input-group-control]:focus-visible]:ring-ring/50 dark:bg-input/30']) }}
>
    <input
        role="combobox"
        autocomplete="off"
        x-init="registerInput($el)"
        x-model="query"
        x-on:input="filter()"
        x-on:focus="handleFocus()"
        x-on:click="if (! open) openList();"
        x-on:keydown="handleKeydown($event)"
        x-bind:disabled="disabled || @js($disabled)"
        x-bind:aria-controls="contentId"
        x-bind:aria-expanded="open"
        x-bind:aria-activedescendant="open && visibleOptions[activeIndex] ? visibleOptions[activeIndex].id : null"
        data-slot="input-group-control"
        {{ $attributes->except(['class', 'showTrigger', 'showClear'])->twMerge(['class' => 'h-9 min-w-0 flex-1 rounded-none border-0 bg-transparent px-3 py-1 text-base shadow-none outline-none placeholder:text-muted-foreground disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm']) }}
    />
    @if ($showTrigger || $showClear)
        <div data-slot="input-group-addon" data-align="inline-end" class="order-last flex items-center pr-3">
            @if ($showClear)
                <x-ui.combobox-clear />
            @endif
            @if ($showTrigger)
                <x-ui.combobox-trigger
                    @if($showClear) x-show="multiple ? ! value.length : value === null || value === ''" @endif
                />
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
