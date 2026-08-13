<div
    role="listbox"
    x-ref="list"
    tabindex="-1"
    :aria-multiselectable="multiple"
    :id="$id('ui-combobox-list')"
    @keydown.down.prevent="move(1)"
    @keydown.up.prevent="move(-1)"
    @keydown.home.prevent="edge('first')"
    @keydown.end.prevent="edge('last')"
    @keydown.enter.prevent="selectActive()"
    data-slot="combobox-list"
    {{ $attributes->twMerge('max-h-[300px] min-h-0 scroll-py-1 overflow-x-hidden overflow-y-auto p-1 outline-hidden') }}
>
    {{ $slot }}
</div>
