import { anchor } from '@alpinejs/anchor';
import { use } from './lib/plugins.js';
import { menuNavigation, dropdownMenuCheckbox, dropdownMenuRadioGroup, dropdownMenuSub } from './lib/menu.js';

const contextMenu = (open = false) => ({
    ...menuNavigation,
    open,
    trigger: null,
    content: null,
    x: 0,
    y: 0,
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'menu');
    },
    registerContent(content) {
        this.content = content;

        if (this.open) {
            this.$nextTick(() => this.positionContent());
        }
    },
    openMenu(event) {
        this.trigger = event.currentTarget;

        if (event.type === 'contextmenu') {
            this.x = event.clientX;
            this.y = event.clientY;
        } else {
            const rect = this.trigger.getBoundingClientRect();
            this.x = rect.left;
            this.y = rect.bottom;
        }

        this.open = true;
        this.$nextTick(() => {
            this.positionContent();
            this.focusItem(this.content, 'first');
        });
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
    handleTriggerKeydown(event) {
        if (event.key === 'ContextMenu' || (event.shiftKey && event.key === 'F10')) {
            event.preventDefault();
            this.openMenu(event);
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
    positionContent() {
        if (!this.content) {
            return;
        }

        const padding = 8;
        const left = Math.min(this.x, Math.max(padding, window.innerWidth - this.content.offsetWidth - padding));
        const top = Math.min(this.y, Math.max(padding, window.innerHeight - this.content.offsetHeight - padding));

        Object.assign(this.content.style, {
            left: `${Math.max(padding, left)}px`,
            top: `${Math.max(padding, top)}px`,
        });
        this.content.dataset.side = top < this.y ? 'top' : 'bottom';
    },
});

export default function (Alpine) {
    use(Alpine, anchor);
    Alpine.data('uiContextMenu', contextMenu);
    Alpine.data('uiContextMenuCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiContextMenuRadioGroup', dropdownMenuRadioGroup);
    Alpine.data('uiContextMenuSub', dropdownMenuSub);
}
