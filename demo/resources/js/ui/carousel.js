const carousel = (orientation = 'horizontal') => ({
    orientation,
    viewport: null,
    resizeObserver: null,
    canScrollPrevious: false,
    canScrollNext: false,
    initialize(viewport) {
        this.viewport = viewport;
        this.resizeObserver = new ResizeObserver(() => this.update());
        this.resizeObserver.observe(viewport);
        this.$nextTick(() => this.update());
    },
    update() {
        if (!this.viewport) {
            return;
        }

        const position = this.orientation === 'vertical' ? this.viewport.scrollTop : this.viewport.scrollLeft;
        const viewportSize = this.orientation === 'vertical' ? this.viewport.clientHeight : this.viewport.clientWidth;
        const scrollSize = this.orientation === 'vertical' ? this.viewport.scrollHeight : this.viewport.scrollWidth;

        this.canScrollPrevious = position > 1;
        this.canScrollNext = position < scrollSize - viewportSize - 1;
    },
    scroll(direction) {
        const item = this.viewport?.querySelector('[data-slot="carousel-item"]');

        if (!item) {
            return;
        }

        this.viewport.scrollBy({
            [this.orientation === 'vertical' ? 'top' : 'left']: direction * (this.orientation === 'vertical' ? item.offsetHeight : item.offsetWidth),
            behavior: 'smooth',
        });
    },
    scrollPrevious() {
        this.scroll(-1);
    },
    scrollNext() {
        this.scroll(1);
    },
    handleKeydown(event) {
        const directions = this.orientation === 'vertical'
            ? { ArrowUp: -1, ArrowDown: 1 }
            : { ArrowLeft: -1, ArrowRight: 1 };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.scroll(directions[event.key]);
        }
    },
});

export default function (Alpine) {
    Alpine.data('uiCarousel', carousel);
}
