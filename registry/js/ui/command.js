const command = () => ({
    query: '',
    root: null,
    activeIndex: -1,
    visibleCount: 0,
    get items() {
        return this.root ? [...this.root.querySelectorAll('[role="option"]')] : [];
    },
    get visibleItems() {
        return this.items.filter((item) => !item.hidden && !item.hasAttribute('data-disabled'));
    },
    initialize(root) {
        this.root = root;
        this.$nextTick(() => this.filter());
    },
    matches(item) {
        const query = this.query.trim().toLocaleLowerCase();
        const value = item.dataset.value || item.textContent.trim();

        return !query || value.toLocaleLowerCase().includes(query);
    },
    filter(query = this.query) {
        this.query = query;
        this.items.forEach((item) => {
            item.hidden = !this.matches(item);
            item.style.display = item.hidden ? 'none' : '';
        });
        this.root.querySelectorAll('[data-slot="command-group"]').forEach((group) => {
            group.hidden = !group.querySelector('[role="option"]:not([hidden])');
            group.style.display = group.hidden ? 'none' : '';
        });
        this.root.querySelectorAll('[data-slot="command-separator"]').forEach((separator) => {
            let before = separator.previousElementSibling;
            let after = separator.nextElementSibling;

            while (before && before.dataset.slot !== 'command-group') {
                before = before.previousElementSibling;
            }
            while (after && after.dataset.slot !== 'command-group') {
                after = after.nextElementSibling;
            }

            separator.hidden = !before || before.hidden || !after || after.hidden;
            separator.style.display = separator.hidden ? 'none' : '';
        });
        this.visibleCount = this.visibleItems.length;
        this.activeIndex = this.visibleItems.length ? 0 : -1;
        this.updateSelection();
    },
    activate(item) {
        if (item.hidden || item.hasAttribute('data-disabled')) {
            return;
        }

        this.activeIndex = this.visibleItems.indexOf(item);
        this.updateSelection();
    },
    move(direction) {
        const items = this.visibleItems;

        if (!items.length) {
            return;
        }

        if (direction === 'first' || direction === 'last') {
            this.activeIndex = direction === 'first' ? 0 : items.length - 1;
        } else {
            this.activeIndex = (this.activeIndex + direction + items.length) % items.length;
        }

        this.updateSelection();
        items[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
    },
    updateSelection() {
        this.items.forEach((item) => {
            item.dataset.selected = 'false';
            item.setAttribute('aria-selected', 'false');
        });

        if (this.activeIndex >= 0) {
            this.visibleItems[this.activeIndex].dataset.selected = 'true';
            this.visibleItems[this.activeIndex].setAttribute('aria-selected', 'true');
        }
    },
    select(item) {
        if (!item || item.hasAttribute('data-disabled')) {
            return;
        }

        this.$dispatch('select', item.dataset.value);
    },
    handleKeydown(event) {
        if (event.key === 'Enter' && this.activeIndex >= 0) {
            event.preventDefault();
            this.select(this.visibleItems[this.activeIndex]);
            return;
        }

        const directions = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.move(directions[event.key]);
        }
    },
});

export default function (Alpine) {
    Alpine.data('uiCommand', command);
}
