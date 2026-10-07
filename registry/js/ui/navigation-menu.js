import { nextId } from './lib/id.js';

const navigationMenu = (value = null, usesViewport = true) => ({
    value,
    usesViewport,
    trigger: null,
    root: null,
    viewport: null,
    indicator: null,
    contents: [],
    closeTimer: null,
    id: nextId(),
    itemValue(element) {
        return element.closest('[data-slot="navigation-menu-item"]')?.dataset.value ?? null;
    },
    initialize(root) {
        this.root = root;
    },
    triggerId(item) {
        return `ui-navigation-menu-${this.id}-trigger-${encodeURIComponent(item ?? '')}`;
    },
    contentId(item) {
        return `ui-navigation-menu-${this.id}-content-${encodeURIComponent(item ?? '')}`;
    },
    isOpen(item) {
        return this.value === item;
    },
    registerTrigger(trigger) {
        const item = this.itemValue(trigger);

        trigger.id = this.triggerId(item);
        trigger.setAttribute('aria-controls', this.contentId(item));
    },
    registerContent(content) {
        const item = this.itemValue(content);

        content.dataset.value = item;
        content.id = this.contentId(item);
        content.setAttribute('aria-labelledby', this.triggerId(item));
        this.contents.push(content);

        if (this.viewport) {
            content.style.position = 'absolute';
            this.viewport.append(content);
        }
    },
    registerViewport(viewport) {
        this.viewport = viewport;
        this.contents.forEach((content) => {
            content.style.position = 'absolute';
            viewport.append(content);
        });
    },
    registerIndicator(indicator) {
        this.indicator = indicator;
    },
    openMenu(item, trigger = null, focus = false) {
        if (!item || trigger?.disabled) {
            return;
        }

        clearTimeout(this.closeTimer);
        this.value = item;
        this.trigger = trigger ?? this.trigger;
        this.$nextTick(() => this.updateLayout());

        if (focus) {
            this.$nextTick(() => this.content(item)?.querySelector('a[href], button:not(:disabled), [tabindex]:not([tabindex="-1"])')?.focus());
        }
    },
    toggleMenu(item, trigger) {
        this.isOpen(item) ? this.closeMenu(true) : this.openMenu(item, trigger);
    },
    closeMenu(restoreFocus = false) {
        const trigger = this.trigger;

        this.value = null;
        this.trigger = null;

        if (restoreFocus) {
            this.$nextTick(() => trigger?.focus());
        }
    },
    scheduleClose() {
        clearTimeout(this.closeTimer);
        this.closeTimer = setTimeout(() => this.closeMenu(), 150);
    },
    content(item) {
        return document.getElementById(this.contentId(item));
    },
    updateLayout() {
        const content = this.content(this.value);

        if (this.viewport && content) {
            this.viewport.style.width = `${content.offsetWidth}px`;
            this.viewport.style.height = `${content.offsetHeight}px`;

            if (this.trigger && this.root) {
                const rootRect = this.root.getBoundingClientRect();
                const triggerRect = this.trigger.getBoundingClientRect();
                const minimumLeft = 8 - rootRect.left;
                const maximumLeft = window.innerWidth - content.offsetWidth - 8 - rootRect.left;
                const triggerLeft = triggerRect.left - rootRect.left;

                this.viewport.style.left = `${Math.max(minimumLeft, Math.min(triggerLeft, maximumLeft))}px`;
            }
        }

        if (this.indicator && this.trigger && this.root) {
            const rootRect = this.root.getBoundingClientRect();
            const triggerRect = this.trigger.getBoundingClientRect();

            this.indicator.style.left = `${triggerRect.left - rootRect.left}px`;
            this.indicator.style.width = `${triggerRect.width}px`;
        }
    },
    triggers(list) {
        return [...list.querySelectorAll(':scope > [data-slot="navigation-menu-item"] > [data-slot="navigation-menu-trigger"], :scope > [data-slot="navigation-menu-item"] > [data-slot="navigation-menu-link"]')]
            .filter((item) => !item.hasAttribute('disabled') && !item.hasAttribute('data-disabled'));
    },
    move(list, direction) {
        const triggers = this.triggers(list);
        const current = triggers.indexOf(document.activeElement);
        const next = direction === 'first'
            ? triggers[0]
            : direction === 'last'
                ? triggers.at(-1)
                : triggers[(current + direction + triggers.length) % triggers.length];

        next?.focus();

        if (this.value && next?.dataset.slot === 'navigation-menu-trigger') {
            this.openMenu(this.itemValue(next), next);
        }
    },
    handleListKeydown(event) {
        const directions = { ArrowRight: 1, ArrowLeft: -1, Home: 'first', End: 'last' };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.move(event.currentTarget, directions[event.key]);
        } else if (event.key === 'Escape' && this.value) {
            event.preventDefault();
            this.closeMenu(true);
        }
    },
    handleTriggerKeydown(event, item) {
        if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.openMenu(item, event.currentTarget, true);
        }
    },
    handleContentKeydown(event) {
        const links = [...event.currentTarget.querySelectorAll('a[href], button:not(:disabled), [tabindex]:not([tabindex="-1"])')];
        const current = links.indexOf(document.activeElement);

        if (event.key === 'Escape') {
            event.preventDefault();
            this.closeMenu(true);
        } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            links[(current + (event.key === 'ArrowDown' ? 1 : -1) + links.length) % links.length]?.focus();
        } else if (event.key === 'Tab' && ((!event.shiftKey && current === links.length - 1) || (event.shiftKey && current === 0))) {
            this.closeMenu();
        }
    },
});

export default function (Alpine) {
    Alpine.data('uiNavigationMenu', navigationMenu);
}
