import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';
import { menuNavigation, dropdownMenuCheckbox, dropdownMenuRadioGroup, dropdownMenuSub } from './lib/menu.js';

const dropdownMenu = (open = false) => ({
    ...menuNavigation,
    open,
    trigger: null,
    content: null,
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'menu');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    registerContent(content) {
        this.content = content;
    },
    openMenu(focus = 'first') {
        this.open = true;
        this.$nextTick(() => this.focusItem(this.content, focus));
    },
    closeMenu(restoreFocus = true) {
        if (!this.open) {
            return;
        }

        this.open = false;

        if (restoreFocus) {
            this.$nextTick(() => this.trigger?.focus());
        }
    },
    toggleMenu() {
        this.open ? this.closeMenu(false) : this.openMenu();
    },
    handleTriggerKeydown(event) {
        if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.openMenu('first');
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.openMenu('last');
        }
    },
    handleKeydown(event) {
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
    Alpine.data('uiDropdownMenu', dropdownMenu);
    Alpine.data('uiDropdownMenuCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiDropdownMenuRadioGroup', dropdownMenuRadioGroup);
    Alpine.data('uiDropdownMenuSub', dropdownMenuSub);
}
