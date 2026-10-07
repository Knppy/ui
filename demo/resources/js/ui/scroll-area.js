const scrollArea = () => ({
    root: null,
    viewport: null,
    resizeObserver: null,
    mutationObserver: null,
    initialize(root) {
        this.root = root;
        this.viewport = root.querySelector('[data-slot="scroll-area-viewport"]');
        this.$nextTick(() => this.update());
        this.resizeObserver = new ResizeObserver(() => this.update());
        this.resizeObserver.observe(this.viewport);
        this.mutationObserver = new MutationObserver(() => this.$nextTick(() => this.update()));
        this.mutationObserver.observe(this.viewport, { childList: true, subtree: true, characterData: true });
    },
    update() {
        if (!this.viewport) {
            return;
        }

        this.updateThumb('vertical', this.viewport.clientHeight, this.viewport.scrollHeight, this.viewport.scrollTop);
        this.updateThumb('horizontal', this.viewport.clientWidth, this.viewport.scrollWidth, this.viewport.scrollLeft);
    },
    updateThumb(orientation, viewportSize, scrollSize, scrollPosition) {
        const scrollbar = this.root.querySelector(`[data-orientation="${orientation}"]`);
        const thumb = scrollbar?.querySelector('[data-slot="scroll-area-thumb"]');

        if (!scrollbar || !thumb) {
            return;
        }

        const trackSize = orientation === 'vertical' ? scrollbar.clientHeight : scrollbar.clientWidth;
        const thumbSize = scrollSize > 0 ? Math.max(18, trackSize * viewportSize / scrollSize) : trackSize;
        const travel = Math.max(0, trackSize - thumbSize);
        const maximum = Math.max(0, scrollSize - viewportSize);
        const offset = maximum > 0 ? travel * scrollPosition / maximum : 0;

        scrollbar.hidden = maximum <= 1;
        thumb.style[orientation === 'vertical' ? 'height' : 'width'] = `${thumbSize}px`;
        thumb.style.transform = orientation === 'vertical'
            ? `translateY(${offset}px)`
            : `translateX(${offset}px)`;
    },
    startDrag(event, orientation) {
        event.preventDefault();
        const scrollbar = event.currentTarget;
        const thumb = scrollbar.querySelector('[data-slot="scroll-area-thumb"]');
        const startPointer = orientation === 'vertical' ? event.clientY : event.clientX;
        const startScroll = orientation === 'vertical' ? this.viewport.scrollTop : this.viewport.scrollLeft;
        const trackSize = orientation === 'vertical' ? scrollbar.clientHeight : scrollbar.clientWidth;
        const thumbSize = orientation === 'vertical' ? thumb.clientHeight : thumb.clientWidth;
        const maximum = orientation === 'vertical'
            ? this.viewport.scrollHeight - this.viewport.clientHeight
            : this.viewport.scrollWidth - this.viewport.clientWidth;
        const move = (moveEvent) => {
            const pointer = orientation === 'vertical' ? moveEvent.clientY : moveEvent.clientX;
            const next = startScroll + (pointer - startPointer) * maximum / Math.max(1, trackSize - thumbSize);

            if (orientation === 'vertical') {
                this.viewport.scrollTop = next;
            } else {
                this.viewport.scrollLeft = next;
            }
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
    },
});

export default function (Alpine) {
    Alpine.data('uiScrollArea', scrollArea);
}
