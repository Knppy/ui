import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';
import { menuNavigation, dropdownMenuCheckbox, dropdownMenuSub } from './lib/menu.js';

const menubarRadioGroup = (value = null) => ({
    selectedValue: value,
    selectValue(value) {
        this.selectedValue = value;
        this.$dispatch('change', value);
        this.closeMenu?.();
    },
});

const menubar = (value = null) => ({
    ...menuNavigation,
    activeMenu: value,
    triggers: {},
    contents: {},
    get value() {
        return this.activeMenu;
    },
    set value(value) {
        this.activeMenu = value;
    },
    registerTrigger(menu, trigger) {
        this.triggers[menu] = trigger;
        trigger?.setAttribute('aria-haspopup', 'menu');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    registerContent(menu, content) {
        this.contents[menu] = content;
    },
    menuValue(element) {
        return element.closest('[data-slot="menubar-menu"]')?.dataset.value ?? this.menu;
    },
    triggerFor(menu) {
        return this.triggers[menu] ?? null;
    },
    isOpen(menu) {
        return this.activeMenu === menu;
    },
    openMenu(menu, focus = null) {
        this.activeMenu = menu;

        if (focus) {
            this.$nextTick(() => this.focusItem(this.contents[menu], focus));
        }
    },
    closeMenu(restoreFocus = true) {
        const trigger = this.triggerFor(this.activeMenu);

        this.activeMenu = null;

        if (restoreFocus) {
            this.$nextTick(() => trigger?.focus());
        }
    },
    toggleMenu(menu) {
        this.isOpen(menu) ? this.closeMenu(false) : this.openMenu(menu, 'first');
    },
    rootTriggers(root) {
        return [...root.querySelectorAll('[data-slot="menubar-trigger"]')]
            .filter((trigger) => !trigger.hasAttribute('data-disabled'));
    },
    moveRoot(root, direction) {
        const triggers = this.rootTriggers(root);
        const current = triggers.indexOf(document.activeElement);
        const index = direction === 'first'
            ? 0
            : direction === 'last'
                ? triggers.length - 1
                : (current + direction + triggers.length) % triggers.length;
        const next = triggers[index];

        next?.focus();

        if (this.activeMenu && next) {
            this.openMenu(next.closest('[data-slot="menubar-menu"]')?.dataset.value);
        }
    },
    handleRootKeydown(event) {
        const directions = { ArrowRight: 1, ArrowLeft: -1, Home: 'first', End: 'last' };

        if (event.target.dataset.slot === 'menubar-trigger' && directions[event.key] !== undefined) {
            event.preventDefault();
            this.moveRoot(event.currentTarget, directions[event.key]);
        }
    },
    handleTriggerKeydown(event, menu) {
        if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.openMenu(menu, 'first');
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.openMenu(menu, 'last');
        }
    },
    handleContentKeydown(event) {
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            const root = this.triggerFor(this.activeMenu)?.closest('[role="menubar"]');

            if (root) {
                this.triggerFor(this.activeMenu)?.focus();
                this.moveRoot(root, event.key === 'ArrowRight' ? 1 : -1);
                this.$nextTick(() => this.focusItem(this.contents[this.activeMenu], 'first'));
            }
            return;
        }

        this.handleMenuKeydown(event, () => this.closeMenu());
    },
    selectItem(item) {
        if (!item.hasAttribute('data-disabled')) {
            this.closeMenu();
        }
    },
});

export default function (Alpine) {
    use(Alpine, anchor);
    Alpine.data('uiMenubar', menubar);
    Alpine.data('uiMenubarCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiMenubarRadioGroup', menubarRadioGroup);
    Alpine.data('uiMenubarSub', dropdownMenuSub);
}
