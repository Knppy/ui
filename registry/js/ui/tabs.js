import { nextId } from './lib/id.js';

const tabs = (value = null, orientation = 'horizontal') => ({
    value,
    orientation,
    id: nextId(),
    select(tab) {
        if (!tab.disabled) {
            this.value = tab.dataset.value;
            tab.focus();
        }
    },
    move(event, direction) {
        const tabs = [...event.currentTarget.querySelectorAll('[role="tab"]:not(:disabled)')];
        const current = tabs.indexOf(document.activeElement);
        const next = direction === 'first'
            ? tabs[0]
            : direction === 'last'
                ? tabs.at(-1)
                : tabs[(current + direction + tabs.length) % tabs.length];

        if (next) {
            event.preventDefault();
            this.select(next);
        }
    },
    triggerId(tab) {
        return `ui-tabs-${this.id}-trigger-${tab}`;
    },
    contentId(tab) {
        return `ui-tabs-${this.id}-content-${tab}`;
    },
});

export default function (Alpine) {
    Alpine.data('uiTabs', tabs);
}
