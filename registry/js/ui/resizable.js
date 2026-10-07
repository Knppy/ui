const resizable = (orientation = 'horizontal') => ({
    orientation,
    root: null,
    initialize(root) {
        this.root = root;
        this.$nextTick(() => this.normalizePanels());
    },
    panels() {
        return [...this.root.querySelectorAll(':scope > [data-slot="resizable-panel"]')];
    },
    normalizePanels() {
        const panels = this.panels();
        const unspecified = panels.filter((panel) => !panel.dataset.defaultSize);
        const specified = panels.reduce((total, panel) => total + Number(panel.dataset.defaultSize || 0), 0);
        const fallback = Math.max(0, 100 - specified) / Math.max(1, unspecified.length);

        panels.forEach((panel) => {
            const size = Number(panel.dataset.defaultSize || fallback || (100 / panels.length));
            panel.style.flexBasis = `${size}%`;
        });
        this.updateHandles();
    },
    adjacentPanels(handle) {
        return [handle.previousElementSibling, handle.nextElementSibling];
    },
    limits(panel) {
        return {
            min: Number(panel.dataset.minSize || 0),
            max: Number(panel.dataset.maxSize || 100),
        };
    },
    resize(handle, delta) {
        const [before, after] = this.adjacentPanels(handle);

        if (!before || !after) {
            return;
        }

        const beforeSize = parseFloat(before.style.flexBasis);
        const afterSize = parseFloat(after.style.flexBasis);
        const beforeLimits = this.limits(before);
        const afterLimits = this.limits(after);
        const minimumDelta = Math.max(beforeLimits.min - beforeSize, afterSize - afterLimits.max);
        const maximumDelta = Math.min(beforeLimits.max - beforeSize, afterSize - afterLimits.min);
        const constrained = Math.max(minimumDelta, Math.min(delta, maximumDelta));

        before.style.flexBasis = `${beforeSize + constrained}%`;
        after.style.flexBasis = `${afterSize - constrained}%`;
        this.updateHandles();
    },
    startResize(event) {
        event.preventDefault();
        const handle = event.currentTarget;
        const rect = this.root.getBoundingClientRect();
        const size = this.orientation === 'vertical' ? rect.height : rect.width;
        let previous = this.orientation === 'vertical' ? event.clientY : event.clientX;
        const move = (moveEvent) => {
            const current = this.orientation === 'vertical' ? moveEvent.clientY : moveEvent.clientX;
            this.resize(handle, (current - previous) / size * 100);
            previous = current;
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
    },
    resizeWithKeyboard(event) {
        const directions = this.orientation === 'vertical'
            ? { ArrowUp: -2, ArrowDown: 2 }
            : { ArrowLeft: -2, ArrowRight: 2 };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.resize(event.currentTarget, directions[event.key]);
        }
    },
    updateHandles() {
        this.root.querySelectorAll(':scope > [data-slot="resizable-handle"]').forEach((handle) => {
            const before = handle.previousElementSibling;
            handle.setAttribute('aria-valuenow', String(Math.round(parseFloat(before?.style.flexBasis || '0'))));
        });
    },
});

export default function (Alpine) {
    Alpine.data('uiResizable', resizable);
}
