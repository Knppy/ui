@props([
    'defaultSize' => null,
])

@php
    $id = 'rp-' . Str::random(8);
    // Strip trailing % so "50%" and 50 both work
    $sizeValue = $defaultSize !== null
        ? (float) str_replace('%', '', $defaultSize)
        : null;
@endphp

<div
    data-slot="resizable-panel"
    data-resizable-panel-id="{{ $id }}"
    x-init="
        $nextTick(() => {
            // Walk up to find the immediate parent group — skip any group that is
            // itself a descendant of another group (i.e. this element's own group).
            let groupEl = $el.parentElement;
            while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') {
                groupEl = groupEl.parentElement;
            }
            if (groupEl && groupEl._x_dataStack) {
                const groupData = groupEl._x_dataStack[0];
                if (groupData && groupData.registerPanel) {
                    groupData.registerPanel($el, {{ $sizeValue !== null ? $sizeValue : 'null' }});
                }
            }
        })
    "
    {{ $attributes->twMerge('overflow-auto') }}
>
    {{ $slot }}
</div>
