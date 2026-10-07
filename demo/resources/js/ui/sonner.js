const toastStore = {
    toasts: [],
    nextId: 0,
    add(message, options = {}) {
        const isObject = message !== null && typeof message === 'object';
        const toast = {
            id: ++this.nextId,
            title: isObject ? message.title : message,
            description: isObject ? message.description : options.description,
            type: (isObject ? message.type : options.type) ?? 'default',
            action: (isObject ? message.action : options.action) ?? null,
            duration: Number((isObject ? message.duration : options.duration) ?? 4000),
            remaining: 0,
            startedAt: 0,
            timer: null,
        };

        toast.remaining = toast.duration;
        this.toasts.push(toast);
        this.startTimer(toast);

        return toast.id;
    },
    dismiss(id = null) {
        const removed = id === null ? this.toasts : this.toasts.filter((toast) => toast.id === id);

        removed.forEach((toast) => clearTimeout(toast.timer));
        this.toasts = id === null ? [] : this.toasts.filter((toast) => toast.id !== id);
    },
    pause() {
        this.toasts.forEach((toast) => {
            if (!toast.timer) {
                return;
            }

            clearTimeout(toast.timer);
            toast.timer = null;
            toast.remaining = Math.max(0, toast.remaining - (Date.now() - toast.startedAt));
        });
    },
    resume() {
        this.toasts.forEach((toast) => this.startTimer(toast));
    },
    startTimer(toast) {
        if (!Number.isFinite(toast.remaining) || toast.remaining <= 0) {
            return;
        }

        toast.startedAt = Date.now();
        toast.timer = setTimeout(() => this.dismiss(toast.id), toast.remaining);
    },
};

const sonner = (position = 'bottom-right', expand = false, visibleToasts = 3) => ({
    position,
    expanded: expand,
    visibleToasts: Number(visibleToasts),
    heights: {},
    get toasts() {
        return [...this.$store.toast.toasts].reverse();
    },
    registerToast(toast, element) {
        const update = () => {
            this.heights = { ...this.heights, [toast.id]: element.offsetHeight };
        };

        update();
        new ResizeObserver(update).observe(element);
    },
    offset(index) {
        return this.toasts.slice(0, index).reduce((offset, toast) => offset + (this.heights[toast.id] ?? 76) + 12, 0);
    },
    toastStyle(index) {
        const distance = this.expanded ? this.offset(index) : index * 16;
        const edge = this.position.startsWith('top') ? 'top' : 'bottom';
        const scale = this.expanded ? 1 : Math.max(0.8, 1 - index * 0.05);

        return {
            [edge]: `${distance}px`,
            opacity: index < this.visibleToasts ? 1 : 0,
            pointerEvents: index < this.visibleToasts ? 'auto' : 'none',
            transform: `scale(${scale})`,
            zIndex: this.toasts.length - index,
        };
    },
    viewportStyle() {
        if (!this.toasts.length) {
            return { height: '0px' };
        }

        const visible = Math.min(this.toasts.length, this.visibleToasts);
        const height = this.expanded
            ? this.offset(visible - 1) + (this.heights[this.toasts[visible - 1]?.id] ?? 76)
            : (this.heights[this.toasts[0]?.id] ?? 76) + Math.max(0, visible - 1) * 16;

        return { height: `${height}px` };
    },
    runAction(toast) {
        toast.action?.onClick?.();
        this.$store.toast.dismiss(toast.id);
    },
});

export default function (Alpine) {
    Alpine.store('toast', toastStore);
    Alpine.data('uiSonner', sonner);
    Alpine.magic('toast', () => {
        const notify = (message, options = {}) => Alpine.store('toast').add(message, options);

        ['success', 'info', 'warning', 'error', 'loading'].forEach((type) => {
            notify[type] = (message, options = {}) => notify(message, { ...options, type });
        });
        notify.dismiss = (id = null) => Alpine.store('toast').dismiss(id);

        return notify;
    });
}
