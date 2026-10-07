const menuItems = (menu) => [...menu.querySelectorAll('[role^="menuitem"]')]
    .filter((item) => item.closest('[role="menu"]') === menu && !item.hasAttribute('data-disabled'));

export const menuNavigation = {
    focusItem(menu, direction = 'first') {
        const items = menuItems(menu);
        const current = items.indexOf(document.activeElement);
        const index = direction === 'first'
            ? 0
            : direction === 'last'
                ? items.length - 1
                : (current + direction + items.length) % items.length;

        items[index]?.focus();
    },
    handleMenuKeydown(event, close) {
        const directions = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.focusItem(event.currentTarget, directions[event.key]);
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            close();
            return;
        }

        if (event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
            const items = menuItems(event.currentTarget);
            const query = event.key.toLocaleLowerCase();
            const start = Math.max(0, items.indexOf(document.activeElement) + 1);
            const ordered = [...items.slice(start), ...items.slice(0, start)];
            ordered.find((item) => item.textContent.trim().toLocaleLowerCase().startsWith(query))?.focus();
        }
    },
};

export const dropdownMenuCheckbox = (checked = false) => ({
    checked,
    toggleChecked() {
        this.checked = !this.checked;
        this.$dispatch('change', this.checked);
        this.closeMenu?.();
    },
});

export const dropdownMenuRadioGroup = (value = null) => ({
    value,
    selectValue(value) {
        this.value = value;
        this.$dispatch('change', value);
        this.closeMenu?.();
    },
});

export const dropdownMenuSub = () => ({
    ...menuNavigation,
    open: false,
    trigger: null,
    content: null,
    timer: null,
    registerSubTrigger(trigger) {
        this.trigger = trigger;
    },
    registerSubContent(content) {
        this.content = content;
    },
    openSub(focus = false) {
        clearTimeout(this.timer);
        this.open = true;

        if (focus) {
            this.$nextTick(() => this.focusItem(this.content, 'first'));
        }
    },
    scheduleSubClose() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.open = false, 100);
    },
    closeSub(restoreFocus = false) {
        clearTimeout(this.timer);
        this.open = false;

        if (restoreFocus) {
            this.$nextTick(() => this.trigger?.focus());
        }
    },
    handleSubKeydown(event) {
        this.handleMenuKeydown(event, () => this.closeSub(true));
    },
});
