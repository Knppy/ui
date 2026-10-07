import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';
import { nextId } from './lib/id.js';

const tooltip = (delay = 0) => ({
    open: false,
    timer: null,
    id: nextId(),
    trigger: null,
    get contentId() {
        return `ui-tooltip-${this.id}`;
    },
    registerTrigger(trigger) {
        this.trigger = trigger;

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }

    },
    show() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.open = true, delay);
    },
    hide() {
        clearTimeout(this.timer);
        this.open = false;
    },
});

export default function (Alpine) {
    use(Alpine, anchor);
    Alpine.data('uiTooltip', tooltip);
}
