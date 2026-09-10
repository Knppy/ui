import {anchor} from "@alpinejs/anchor";
import {focus} from "@alpinejs/focus";
import {collapse} from "@alpinejs/collapse";

/**
 * The theme store function.
 *
 * @type {{}}
 */
const themeStore = {
    darkMode: 'class',
    apply() {
        const root = document.documentElement;
        if (this.darkMode !== false) {
            root.classList.toggle('dark', this.isDark);
        }
    },
    attr(root, name, value, fallback) {
        if (value && value !== fallback) {
            root.setAttribute(name, value);
        } else {
            root.removeAttribute(name);
        }
    },
    init() {
        if (!localStorage.getItem('theme:mode')) {
            this.mode = this.darkMode === 'system' ? 'system' : 'light';
        }
        this.apply();

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.mode === 'system') {
                this.apply();
            }
        });
    },
    get isDark() {
        if (this.darkMode === false) {
            return false;
        }

        return this.mode === 'dark' ||
            (this.mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    },
    set(key, value) {
        this[key] = value;
        localStorage.setItem('theme:' + key, value);
        this.apply();
    },

    setMode(mode) {
        this.set('mode', mode);
    },

    toggle() {
        if (this.darkMode === false) {
            return;
        }

        this.setMode(this.isDark ? 'light' : 'dark');
    },
}

let componentId = 0;

const disclosure = (open = false) => ({
    open,
    close() {
        this.open = false;
    },
    toggle() {
        this.open = !this.open;
    },
});

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

const dialog = (open = false) => ({
    open,
    id: ++componentId,
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

const tooltip = (delay = 0) => ({
    open: false,
    timer: null,
    id: ++componentId,
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

const tabs = (value = null, orientation = 'horizontal') => ({
    value,
    orientation,
    id: ++componentId,
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

const toggle = (pressed = false) => ({
    pressed,
    toggle() {
        this.pressed = !this.pressed;
    },
});

const toggleGroup = (value = null, type = 'single') => ({
    value: type === 'multiple' ? (Array.isArray(value) ? value : []) : value,
    type,
    isPressed(item) {
        return this.type === 'multiple' ? this.value.includes(item) : this.value === item;
    },
    toggle(item) {
        if (this.type === 'multiple') {
            this.value = this.isPressed(item)
                ? this.value.filter((value) => value !== item)
                : [...this.value, item];
            return;
        }

        this.value = this.value === item ? null : item;
    },
});

/**
 * Registers all the UI functionality.
 *
 * @param Alpine
 * @param options
 */
export function registerUI(Alpine, options = {}) {
    if (options.darkMode !== undefined) {
        themeStore.darkMode = options.darkMode;
    }

    Alpine.plugin(anchor);
    Alpine.plugin(focus);
    Alpine.plugin(collapse);

    // Stores.
    Alpine.store('theme', themeStore);

    // Data.
    Alpine.data('uiAccordion', accordion);
    Alpine.data('uiCollapsible', disclosure);
    Alpine.data('uiTabs', tabs);
    Alpine.data('uiToggle', toggle);
    Alpine.data('uiToggleGroup', toggleGroup);
    Alpine.data('uiAvatar', () => ({ imageLoaded: false }));
    Alpine.data('uiCheckbox', disclosure);
    Alpine.data('uiDialog', dialog);
    Alpine.data('uiHoverCard', hoverCard);
    Alpine.data('uiPopover', popover);
    Alpine.data('uiRadioGroup', (value = null) => ({ value }));
    Alpine.data('uiSwitch', disclosure);
    Alpine.data('uiTooltip', tooltip);

    // Directives.

    // Magic.

}
