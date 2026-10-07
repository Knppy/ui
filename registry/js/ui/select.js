import { nextId } from './lib/id.js';
import { autoUpdate, computePosition, flip, offset, shift, size } from '@floating-ui/dom';

const select = (value = null, disabled = false) => ({
    open: false,
    value,
    disabled,
    id: nextId(),
    trigger: null,
    content: null,
    position: 'item-aligned',
    side: 'bottom',
    align: 'center',
    sideOffset: 0,
    cleanupPosition: null,
    activeIndex: -1,
    get contentId() {
        return `ui-select-${this.id}-content`;
    },
    get options() {
        return this.content ? [...this.content.querySelectorAll('[role="option"]')] : [];
    },
    get selectableOptions() {
        return this.options.filter((option) => !option.hasAttribute('data-disabled'));
    },
    get selectedOption() {
        return this.options.find((option) => this.isSelected(option.dataset.value)) ?? null;
    },
    get selectedLabel() {
        return this.selectedOption?.querySelector('[data-slot="select-item-text"]')?.textContent.trim() ?? '';
    },
    registerTrigger(trigger) {
        this.trigger = trigger;
    },
    registerContent(content, position = 'item-aligned', side = 'bottom', align = 'center', sideOffset = 0) {
        this.content = content;
        this.position = position;
        this.side = side;
        this.align = align;
        this.sideOffset = Number(sideOffset);
        this.cleanupPosition?.();
        this.cleanupPosition = autoUpdate(this.trigger, content, () => {
            if (this.open) {
                this.positionContent();
            }
        });
    },
    optionId(value) {
        return `ui-select-${this.id}-option-${encodeURIComponent(value ?? '')}`;
    },
    isSelected(optionValue) {
        return String(optionValue ?? '') === String(this.value ?? '');
    },
    openList() {
        if (this.disabled) {
            return;
        }

        this.open = true;
        this.$nextTick(() => {
            const selected = this.selectableOptions.indexOf(this.selectedOption);
            this.activeIndex = selected >= 0 ? selected : 0;
            this.positionContent();
        });
    },
    closeList(focus = false) {
        this.open = false;

        if (focus) {
            this.$nextTick(() => this.trigger?.focus());
        }
    },
    toggleList() {
        this.open ? this.closeList() : this.openList();
    },
    selectOption(option) {
        if (!option || option.hasAttribute('data-disabled')) {
            return;
        }

        this.value = option.dataset.value;
        this.$dispatch('change', this.value);
        this.closeList(true);
    },
    move(direction) {
        const options = this.selectableOptions;

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
        if (this.disabled) {
            return;
        }

        if (event.key === 'Escape' && this.open) {
            event.preventDefault();
            this.closeList(true);
            return;
        }

        if (event.key === 'Tab') {
            this.closeList();
            return;
        }

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.open ? this.selectOption(this.selectableOptions[this.activeIndex]) : this.openList();
            return;
        }

        if (!this.open && event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
            const query = event.key.toLocaleLowerCase();
            const option = this.selectableOptions.find((item) => item.textContent.trim().toLocaleLowerCase().startsWith(query));

            if (option) {
                event.preventDefault();
                this.selectOption(option);
            }

            return;
        }

        const directions = {
            ArrowDown: 1,
            ArrowUp: -1,
            Home: 'first',
            End: 'last',
        };

        if (directions[event.key] !== undefined) {
            event.preventDefault();

            if (!this.open) {
                this.openList();
                return;
            }

            this.move(directions[event.key]);
        }
    },
    scroll(direction) {
        this.content?.scrollBy({ top: direction * 48, behavior: 'smooth' });
    },
    positionContent() {
        if (!this.trigger || !this.content) {
            return;
        }

        const triggerRect = this.trigger.getBoundingClientRect();
        this.content.style.width = `${triggerRect.width}px`;
        this.content.style.maxHeight = `${Math.max(96, window.innerHeight - 16)}px`;

        if (this.position === 'popper') {
            const placement = this.align === 'center' ? this.side : `${this.side}-${this.align}`;

            computePosition(this.trigger, this.content, {
                strategy: 'fixed',
                placement,
                middleware: [
                    offset(this.sideOffset),
                    flip(),
                    shift({ padding: 8 }),
                    size({
                        padding: 8,
                        apply: ({ availableHeight }) => {
                            this.content.style.maxHeight = `${availableHeight}px`;
                        },
                    }),
                ],
            }).then(({ x, y, placement: resolvedPlacement }) => {
                Object.assign(this.content.style, { left: `${x}px`, top: `${y}px` });
                this.content.dataset.side = resolvedPlacement.split('-')[0];
            });

            return;
        }

        const selected = this.selectedOption ?? this.selectableOptions[0];
        const selectedCenter = selected ? selected.offsetTop + selected.offsetHeight / 2 : this.content.offsetHeight / 2;
        const padding = 8;
        const maximumLeft = window.innerWidth - this.content.offsetWidth - padding;
        const maximumTop = window.innerHeight - this.content.offsetHeight - padding;
        const left = Math.min(Math.max(padding, triggerRect.left), Math.max(padding, maximumLeft));
        const top = Math.min(
            Math.max(padding, triggerRect.top + triggerRect.height / 2 - selectedCenter),
            Math.max(padding, maximumTop),
        );

        Object.assign(this.content.style, { left: `${left}px`, top: `${top}px` });
        this.content.dataset.side = top < triggerRect.top ? 'top' : 'bottom';
    },
});

export default function (Alpine) {
    Alpine.data('uiSelect', select);
}
