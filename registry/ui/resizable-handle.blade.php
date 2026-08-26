@props([
    'withHandle' => false,
])

<div
    data-slot="resizable-handle"
    role="separator"
    tabindex="0"
    x-init="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        const isVertical = groupEl && groupEl.dataset.orientation === 'vertical';
        $el.dataset.orientation = isVertical ? 'vertical' : 'horizontal';
        if (isVertical) {
            $el.style.height = '1px';
            $el.style.width = '100%';
        } else {
            $el.style.width = '1px';
            $el.style.alignSelf = 'stretch';
        }
    "
    @pointerdown.prevent="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        if (groupEl && groupEl._x_dataStack) {
            const g = groupEl._x_dataStack[0];
            if (g && g.startDrag) { $el.setPointerCapture($event.pointerId); g.startDrag($el, $event); }
        }
    "
    @keydown.left.prevent="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        if (groupEl && groupEl._x_dataStack) {
            const g = groupEl._x_dataStack[0];
            if (g && g.orientation === 'horizontal') {
                const handles = Array.from(groupEl.children).filter(c => c.dataset.slot === 'resizable-handle');
                const idx = handles.indexOf($el);
                const before = g.panels[idx]; const after = g.panels[idx + 1];
                if (before && after) { const d = Math.min(1, before.size); before.size -= d; after.size += d; g.applyPanelSizes(); }
            }
        }
    "
    @keydown.right.prevent="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        if (groupEl && groupEl._x_dataStack) {
            const g = groupEl._x_dataStack[0];
            if (g && g.orientation === 'horizontal') {
                const handles = Array.from(groupEl.children).filter(c => c.dataset.slot === 'resizable-handle');
                const idx = handles.indexOf($el);
                const before = g.panels[idx]; const after = g.panels[idx + 1];
                if (before && after) { const d = Math.min(1, after.size); before.size += d; after.size -= d; g.applyPanelSizes(); }
            }
        }
    "
    @keydown.up.prevent="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        if (groupEl && groupEl._x_dataStack) {
            const g = groupEl._x_dataStack[0];
            if (g && g.orientation === 'vertical') {
                const handles = Array.from(groupEl.children).filter(c => c.dataset.slot === 'resizable-handle');
                const idx = handles.indexOf($el);
                const before = g.panels[idx]; const after = g.panels[idx + 1];
                if (before && after) { const d = Math.min(1, before.size); before.size -= d; after.size += d; g.applyPanelSizes(); }
            }
        }
    "
    @keydown.down.prevent="
        let groupEl = $el.parentElement;
        while (groupEl && groupEl.dataset.slot !== 'resizable-panel-group') { groupEl = groupEl.parentElement; }
        if (groupEl && groupEl._x_dataStack) {
            const g = groupEl._x_dataStack[0];
            if (g && g.orientation === 'vertical') {
                const handles = Array.from(groupEl.children).filter(c => c.dataset.slot === 'resizable-handle');
                const idx = handles.indexOf($el);
                const before = g.panels[idx]; const after = g.panels[idx + 1];
                if (before && after) { const d = Math.min(1, after.size); before.size += d; after.size -= d; g.applyPanelSizes(); }
            }
        }
    "
    {{ $attributes->twMerge(
        'relative flex shrink-0 items-center justify-center bg-border ' .
        'focus-visible:ring-1 focus-visible:ring-ring focus-visible:ring-offset-1 focus-visible:outline-hidden ' .
        'after:absolute after:left-1/2 after:w-1 after:-translate-x-1/2 after:inset-y-0 ' .
        'data-[orientation=vertical]:cursor-row-resize ' .
        'data-[orientation=horizontal]:cursor-col-resize ' .
        'data-[orientation=vertical]:[&>div]:rotate-90'
    ) }}
>
    @if ($withHandle)
        <div class="z-10 flex h-6 w-1 shrink-0 rounded-lg bg-border">
{{--            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">--}}
{{--                <circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/>--}}
{{--                <circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/>--}}
{{--            </svg>--}}
        </div>
    @endif
</div>
