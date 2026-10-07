import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';

const popover = (open = false) => ({
    open,
    trigger: null,
    close() {
        this.open = false;
    },
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'dialog');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    toggle() {
        this.open = !this.open;
    },
});

export default function (Alpine) {
    use(Alpine, anchor);
    Alpine.data('uiPopover', popover);
}
