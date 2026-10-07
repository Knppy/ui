import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';

const hoverCard = (openDelay = 700, closeDelay = 300) => ({
    open: false,
    openDelay,
    closeDelay,
    trigger: null,
    timer: null,
    registerTrigger(trigger, delay = null, closeDelay = null) {
        this.trigger = trigger;

        if (delay !== null) {
            this.openDelay = delay;
        }

        if (closeDelay !== null) {
            this.closeDelay = closeDelay;
        }

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    scheduleOpen() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.open = true, this.openDelay);
    },
    scheduleClose() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.open = false, this.closeDelay);
    },
    cancelClose() {
        clearTimeout(this.timer);
    },
});

export default function (Alpine) {
    use(Alpine, anchor);
    Alpine.data('uiHoverCard', hoverCard);
}
