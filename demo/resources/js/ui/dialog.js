import { focus } from '@alpinejs/focus';
import { use } from './lib/plugins.js';
import { nextId } from './lib/id.js';

const dialog = (open = false) => ({
    open,
    id: nextId(),
    trigger: null,
    get titleId() {
        return `ui-dialog-${this.id}-title`;
    },
    get descriptionId() {
        return `ui-dialog-${this.id}-description`;
    },
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'dialog');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    openDialog(trigger = null) {
        this.trigger = trigger;
        this.open = true;
        this.$nextTick(() => {
            const form = this.trigger?.closest('form');

            if (form && this.$refs.content) {
                form.id ||= `ui-dialog-${this.id}-form`;
                this.$refs.content.querySelectorAll('button, fieldset, input, object, output, select, textarea')
                    .forEach((control) => control.setAttribute('form', control.getAttribute('form') || form.id));
            }

            this.$refs.content?.focus();
        });
    },
    closeDialog() {
        if (!this.open) {
            return;
        }

        this.open = false;
        this.$nextTick(() => this.trigger?.focus());
    },
});

export default function (Alpine) {
    use(Alpine, focus);
    Alpine.data('uiDialog', dialog);
}
