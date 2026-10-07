const messageScroller = () => ({
    viewport: null,
    resizeObserver: null,
    mutationObserver: null,
    atStart: true,
    atEnd: true,
    initViewport(viewport) {
        this.viewport = viewport;
        this.$nextTick(() => this.scrollToEdge('end', 'auto'));
        this.resizeObserver = new ResizeObserver(() => this.updateScrollState());
        this.resizeObserver.observe(viewport);
        this.mutationObserver = new MutationObserver(() => {
            const shouldScroll = this.atEnd;

            this.$nextTick(() => {
                if (shouldScroll) {
                    this.scrollToEdge('end', 'auto');
                }
                this.updateScrollState();
            });
        });
        this.mutationObserver.observe(viewport, { childList: true, subtree: true, characterData: true });
    },
    updateScrollState() {
        if (!this.viewport) {
            return;
        }

        const maximum = this.viewport.scrollHeight - this.viewport.clientHeight;
        this.atStart = this.viewport.scrollTop <= 1;
        this.atEnd = maximum <= 1 || this.viewport.scrollTop >= maximum - 1;
    },
    scrollToEdge(direction, behavior = 'smooth') {
        if (!this.viewport) {
            return;
        }

        const top = direction === 'start' ? 0 : this.viewport.scrollHeight - this.viewport.clientHeight;

        this.viewport.scrollTo({ top, behavior });
        requestAnimationFrame(() => this.updateScrollState());
    },
    isButtonActive(direction) {
        return direction === 'start' ? !this.atStart : !this.atEnd;
    },
});

export default function (Alpine) {
    Alpine.data('uiMessageScroller', messageScroller);
}
