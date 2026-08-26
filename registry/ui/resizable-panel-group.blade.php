@props([
    'orientation' => 'horizontal',
])

<div
    data-slot="resizable-panel-group"
    data-orientation="{{ $orientation }}"
    x-data="{
        orientation: @js($orientation),
        panels: [],
        dragging: null,
        registerPanel(el, defaultSize) {
            const id = el.getAttribute('data-resizable-panel-id');
            const size = (defaultSize !== null && defaultSize !== undefined)
                ? defaultSize
                : (100 / Math.max(1, Array.from(this.$el.children).filter(c => c.dataset.slot === 'resizable-panel').length));
            this.panels.push({ id, el, size });
            this.$nextTick(() => this.applyPanelSizes());
        },
        applyPanelSizes() {
            const total = this.panels.reduce((s, p) => s + p.size, 0);
            this.panels.forEach(p => {
                const pct = total > 0 ? (p.size / total) * 100 : (100 / this.panels.length);
                p.el.style.flexBasis = pct + '%';
                p.el.style.flexGrow = '0';
                p.el.style.flexShrink = '0';
            });
        },
        startDrag(handleEl, event) {
            const handles = Array.from(this.$el.children).filter(c => c.dataset.slot === 'resizable-handle');
            const idx = handles.indexOf(handleEl);
            if (idx === -1 || idx + 1 >= this.panels.length) return;
            this.dragging = {
                before: this.panels[idx],
                after: this.panels[idx + 1],
                startX: event.clientX,
                startY: event.clientY,
                startBefore: this.panels[idx].size,
                startAfter: this.panels[idx + 1].size,
            };
        },
        onDrag(event) {
            if (! this.dragging) return;
            const d = this.dragging;
            const groupRect = this.$el.getBoundingClientRect();
            const groupSize = this.orientation === 'horizontal' ? groupRect.width : groupRect.height;
            if (groupSize === 0) return;
            const delta = this.orientation === 'horizontal'
                ? ((event.clientX - d.startX) / groupSize) * 100
                : ((event.clientY - d.startY) / groupSize) * 100;
            const newBefore = Math.max(0, Math.min(d.startBefore + d.startAfter, d.startBefore + delta));
            const newAfter = d.startBefore + d.startAfter - newBefore;
            d.before.size = newBefore;
            d.after.size = newAfter;
            this.applyPanelSizes();
        },
        stopDrag() {
            this.dragging = null;
        },
    }"
    @pointermove.window="onDrag($event)"
    @pointerup.window="stopDrag()"
    @pointercancel.window="stopDrag()"
    {{ $attributes->twMerge('flex h-full w-full ' . ($orientation === 'vertical' ? 'flex-col' : '')) }}
>
    {{ $slot }}
</div>
