import { nextId } from './lib/id.js';
import { autoUpdate, computePosition, flip, offset, shift, size } from '@floating-ui/dom';

const combobox = (value = null, multiple = false, disabled = false) => ({
    open: false,
    value: multiple ? (Array.isArray(value) ? value : []) : value,
    multiple,
    disabled,
    query: '',
    searchQuery: '',
    id: nextId(),
    anchor: null,
    input: null,
    content: null,
    activeIndex: -1,
    suppressFocusOpen: false,
    cleanupPosition: null,
    side: 'bottom',
    align: 'start',
    sideOffset: 6,
    alignOffset: 0,
    get contentId() {
        return `ui-combobox-${this.id}-content`;
    },
    get options() {
        return this.content ? [...this.content.querySelectorAll('[role="option"]')] : [];
    },
    get visibleOptions() {
        return this.options.filter((option) => !option.hasAttribute('data-disabled') && this.matches(option));
    },
    get selectedOption() {
        return this.options.find((option) => this.isSelected(option.dataset.value)) ?? null;
    },
    get selectedLabel() {
        return this.selectedOption?.dataset.label ?? '';
    },
    registerAnchor(anchor) {
        this.anchor = anchor;
    },
    registerInput(input) {
        this.input = input;
        this.$nextTick(() => {
            if (!this.multiple && !this.query) {
                this.query = this.selectedLabel;
            }
        });
    },
    handleFocus() {
        if (!this.suppressFocusOpen) {
            this.openList();
        }
    },
    focusInput() {
        if (!this.input) {
            return;
        }

        this.suppressFocusOpen = true;
        this.input.focus();
        this.suppressFocusOpen = false;
    },
    registerContent(content, side = 'bottom', align = 'start', sideOffset = 6, alignOffset = 0) {
        this.content = content;
        this.side = side;
        this.align = align;
        this.sideOffset = Number(sideOffset);
        this.alignOffset = Number(alignOffset);
        this.cleanupPosition?.();
        this.cleanupPosition = autoUpdate(this.anchor ?? this.input, content, () => {
            if (this.open) {
                this.positionContent();
            }
        });
        this.$nextTick(() => {
            if (!this.multiple && !this.query) {
                this.query = this.selectedLabel;
            }
        });
    },
    optionId(value) {
        return `ui-combobox-${this.id}-option-${encodeURIComponent(value ?? '')}`;
    },
    isSelected(optionValue) {
        const normalized = String(optionValue ?? '');

        return this.multiple
            ? this.value.map(String).includes(normalized)
            : String(this.value ?? '') === normalized;
    },
    matches(option) {
        return !this.searchQuery || option.dataset.label.toLocaleLowerCase().includes(this.searchQuery.trim().toLocaleLowerCase());
    },
    openList(resetQuery = true) {
        if (this.disabled) {
            return;
        }

        if (!this.open && resetQuery) {
            this.query = this.multiple ? '' : this.selectedLabel;
            this.searchQuery = '';
        }

        this.open = true;
        this.$nextTick(() => {
            const selected = this.visibleOptions.indexOf(this.selectedOption);
            this.activeIndex = selected >= 0 ? selected : (this.visibleOptions.length ? 0 : -1);
            this.positionContent();
        });
    },
    closeList() {
        this.open = false;

        if (!this.multiple) {
            this.query = this.selectedLabel;
        }
        this.searchQuery = '';
    },
    toggleList() {
        this.open ? this.closeList() : this.openList();
    },
    filter() {
        this.searchQuery = this.query;
        this.openList(false);
        this.$nextTick(() => {
            this.activeIndex = this.visibleOptions.length ? 0 : -1;
            this.positionContent();
        });
    },
    clear() {
        this.value = this.multiple ? [] : null;
        this.query = '';
        this.searchQuery = '';
        this.$dispatch('change', this.value);
        this.focusInput();
        this.openList(false);
    },
    selectOption(option) {
        if (!option || option.hasAttribute('data-disabled')) {
            return;
        }

        const optionValue = option.dataset.value;

        if (this.multiple) {
            this.value = this.isSelected(optionValue)
                ? this.value.filter((value) => String(value) !== optionValue)
                : [...this.value, optionValue];
            this.query = '';
            this.searchQuery = '';
            this.$dispatch('change', this.value);
            this.$nextTick(() => this.focusInput());
            return;
        }

        this.value = optionValue;
        this.query = option.dataset.label;
        this.searchQuery = '';
        this.$dispatch('change', this.value);
        this.closeList();
        this.$nextTick(() => this.focusInput());
    },
    removeValue(optionValue) {
        if (this.disabled) {
            return;
        }

        this.value = this.multiple
            ? this.value.filter((value) => String(value) !== String(optionValue))
            : null;
        this.query = '';
        this.searchQuery = '';
        this.$dispatch('change', this.value);
    },
    move(direction) {
        const options = this.visibleOptions;

        if (!options.length) {
            return;
        }

        if (direction === 'first' || direction === 'last') {
            this.activeIndex = direction === 'first' ? 0 : options.length - 1;
        } else {
            this.activeIndex = (this.activeIndex + direction + options.length) % options.length;
        }

        options[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
    },
    handleKeydown(event) {
        if (event.key === 'Escape') {
            if (this.open) {
                event.preventDefault();
                this.closeList();
            }
            return;
        }

        if (event.key === 'Tab') {
            this.closeList();
            return;
        }

        if (event.key === 'Enter' && this.open && this.activeIndex >= 0) {
            event.preventDefault();
            this.selectOption(this.visibleOptions[this.activeIndex]);
            return;
        }

        const directions = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.open ? this.move(directions[event.key]) : this.openList();
        }
    },
    positionContent() {
        const anchor = this.anchor ?? this.input;

        if (!anchor || !this.content) {
            return;
        }

        computePosition(anchor, this.content, {
            strategy: 'fixed',
            placement: this.align === 'center' ? this.side : `${this.side}-${this.align}`,
            middleware: [
                offset({ mainAxis: this.sideOffset, crossAxis: this.alignOffset }),
                flip(),
                shift({ padding: 8 }),
                size({
                    padding: 8,
                    apply: ({ availableHeight, availableWidth, rects }) => {
                        this.content.style.width = `${rects.reference.width}px`;
                        this.content.style.maxWidth = `${availableWidth}px`;
                        this.content.style.maxHeight = `${availableHeight}px`;
                    },
                }),
            ],
        }).then(({ x, y, placement }) => {
            Object.assign(this.content.style, { left: `${x}px`, top: `${y}px` });
            this.content.dataset.side = placement.split('-')[0];
        });
    },
    destroy() {
        this.cleanupPosition?.();
    },
});

export default function (Alpine) {
    Alpine.data('uiCombobox', combobox);
}
