import { focus } from '@alpinejs/focus';
import { use } from './lib/plugins.js';
import { nextId } from './lib/id.js';

const drawer = (open = false, direction = 'bottom', dismissible = true) => ({
    open,
    direction,
    dismissible,
    id: nextId(),
    trigger: null,
    get titleId() {
        return `ui-drawer-${this.id}-title`;
    },
    get descriptionId() {
        return `ui-drawer-${this.id}-description`;
    },
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'dialog');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    openDrawer(trigger = null) {
        this.trigger = trigger ?? this.trigger;
        this.open = true;
        this.$nextTick(() => this.$refs.content?.focus());
    },
    closeDrawer() {
        if (!this.open) {
            return;
        }

        this.open = false;
        this.$nextTick(() => this.trigger?.focus());
    },
    startDrag(event) {
        if (!this.dismissible || event.button !== 0) {
            return;
        }

        const content = event.currentTarget;
        const vertical = this.direction === 'top' || this.direction === 'bottom';
        const start = vertical ? event.clientY : event.clientX;
        const startedAt = performance.now();
        let distance = 0;
        const sign = this.direction === 'top' || this.direction === 'left' ? -1 : 1;
        const move = (moveEvent) => {
            const current = vertical ? moveEvent.clientY : moveEvent.clientX;
            distance = Math.max(0, (current - start) * sign);
            content.style.transition = 'none';
            content.style.transform = vertical
                ? `translateY(${distance * sign}px)`
                : `translateX(${distance * sign}px)`;
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
            const size = vertical ? content.offsetHeight : content.offsetWidth;
            const velocity = distance / Math.max(1, performance.now() - startedAt);

            content.style.transition = '';
            content.style.transform = '';

            if (distance > size * 0.25 || velocity > 0.5) {
                this.closeDrawer();
            }
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
    },
});

export default function (Alpine) {
    use(Alpine, focus);
    Alpine.data('uiDrawer', drawer);
}
