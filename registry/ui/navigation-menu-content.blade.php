@props([])

{{--
    Each content panel teleports to body and anchors to the navigation-menu list
    via _listEl (a direct DOM reference stored on the Alpine data object, set by
    navigation-menu-list on init). This survives teleport where $root = body.
    Only the active panel is shown via isOpen(_itemValue).
    The separate <x-navigation-menu-viewport> provides the animated background shell.
--}}
<template x-teleport="body">
    <div
        data-slot="navigation-menu-content"
        x-show="isOpen(_itemValue)"
        x-cloak
        x-init="
            $nextTick(() => {
                if (_items[_itemValue]) {
                    _items[_itemValue].content = $el;
                } else {
                    registerItem(_itemValue, null, $el);
                }
            })
        "
        x-ui-anchor.bottom-start.offset.4.no-min-width="() => _items[_itemValue]?.trigger || _listEl"
        @mouseenter="cancelClose()"
        @mouseleave="close()"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->twMerge('z-50 overflow-hidden rounded-md border bg-popover p-2 text-popover-foreground shadow') }}
    >
        {{ $slot }}
    </div>
</template>
