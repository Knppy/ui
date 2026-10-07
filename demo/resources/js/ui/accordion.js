import { collapse } from '@alpinejs/collapse';
import { use } from './lib/plugins.js';

const accordion = (value = null, type = 'single', collapsible = true) => ({
    value: type === 'multiple' ? (Array.isArray(value) ? value : []) : value,
    isOpen(item) {
        return this.type === 'multiple' ? this.value.includes(item) : this.value === item;
    },
    toggle(item) {
        if (this.type === 'multiple') {
            this.value = this.isOpen(item)
                ? this.value.filter((value) => value !== item)
                : [...this.value, item];
            return;
        }

        if (this.value !== item || this.collapsible) {
            this.value = this.value === item ? null : item;
        }
    },
    type,
    collapsible,
});

export default function (Alpine) {
    use(Alpine, collapse);
    Alpine.data('uiAccordion', accordion);
}
