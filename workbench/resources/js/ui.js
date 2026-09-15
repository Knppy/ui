import {anchor} from "@alpinejs/anchor";
import {focus} from "@alpinejs/focus";
import {collapse} from "@alpinejs/collapse";
import {autoUpdate, computePosition, flip, offset, shift, size} from "@floating-ui/dom";

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

const drawer = (open = false, direction = 'bottom', dismissible = true) => ({
    open,
    direction,
    dismissible,
    id: ++componentId,
    trigger: null,
    get titleId() {
        return `ui-drawer-${this.id}-title`;
    },
    get descriptionId() {
        return `ui-drawer-${this.id}-description`;
    },
    registerTrigger(trigger) {
        this.trigger = trigger;
        trigger?.setAttribute('aria-haspopup', 'dialog');

        if (trigger instanceof HTMLButtonElement && !trigger.hasAttribute('type')) {
            trigger.type = 'button';
        }
    },
    openDrawer(trigger = null) {
        this.trigger = trigger ?? this.trigger;
        this.open = true;
        this.$nextTick(() => this.$refs.content?.focus());
    },
    closeDrawer() {
        if (!this.open) {
            return;
        }

        this.open = false;
        this.$nextTick(() => this.trigger?.focus());
    },
    startDrag(event) {
        if (!this.dismissible || event.button !== 0) {
            return;
        }

        const content = event.currentTarget;
        const vertical = this.direction === 'top' || this.direction === 'bottom';
        const start = vertical ? event.clientY : event.clientX;
        const startedAt = performance.now();
        let distance = 0;
        const sign = this.direction === 'top' || this.direction === 'left' ? -1 : 1;
        const move = (moveEvent) => {
            const current = vertical ? moveEvent.clientY : moveEvent.clientX;
            distance = Math.max(0, (current - start) * sign);
            content.style.transition = 'none';
            content.style.transform = vertical
                ? `translateY(${distance * sign}px)`
                : `translateX(${distance * sign}px)`;
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
            const size = vertical ? content.offsetHeight : content.offsetWidth;
            const velocity = distance / Math.max(1, performance.now() - startedAt);

            content.style.transition = '';
            content.style.transform = '';

            if (distance > size * 0.25 || velocity > 0.5) {
                this.closeDrawer();
            }
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
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

const navigationMenu = (value = null, usesViewport = true) => ({
    value,
    usesViewport,
    trigger: null,
    root: null,
    viewport: null,
    indicator: null,
    contents: [],
    closeTimer: null,
    id: ++componentId,
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

const combobox = (value = null, multiple = false, disabled = false) => ({
    open: false,
    value: multiple ? (Array.isArray(value) ? value : []) : value,
    multiple,
    disabled,
    query: '',
    searchQuery: '',
    id: ++componentId,
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

const select = (value = null, disabled = false) => ({
    open: false,
    value,
    disabled,
    id: ++componentId,
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

const slider = (value = 0, min = 0, max = 100, step = 1) => ({
    value: Number(value),
    min: Number(min),
    max: Number(max),
    step: Number(step),
    get percentage() {
        const range = this.max - this.min;

        return range > 0 ? Math.min(100, Math.max(0, ((this.value - this.min) / range) * 100)) : 0;
    },
});

const calendar = (value = null, mode = 'single', initialMonth = null, showOutsideDays = true, min = null, max = null, startMonth = null, endMonth = null) => ({
    value,
    mode,
    showOutsideDays,
    min,
    max,
    startMonth,
    endMonth,
    month: null,
    focusedDate: null,
    dayFocused: false,
    init() {
        const selected = this.mode === 'range' ? this.value?.from : (Array.isArray(this.value) ? this.value[0] : this.value);
        const date = this.parseDate(initialMonth || selected) ?? new Date();

        this.month = new Date(date.getFullYear(), date.getMonth(), 1);
        this.focusedDate = selected || this.dateKey(date);
    },
    parseDate(value) {
        if (!value) {
            return null;
        }

        const [year, month, day] = String(value).split('-').map(Number);
        const date = new Date(year, month - 1, day || 1);

        return Number.isNaN(date.getTime()) ? null : date;
    },
    dateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    },
    get monthLabel() {
        return this.month?.toLocaleDateString(undefined, { month: 'long', year: 'numeric' }) ?? '';
    },
    get displayMonth() {
        return this.month?.getMonth() ?? 0;
    },
    get displayYear() {
        return this.month?.getFullYear() ?? new Date().getFullYear();
    },
    get monthOptions() {
        return Array.from({ length: 12 }, (_, value) => ({
            value,
            label: new Date(2024, value, 1).toLocaleDateString(undefined, { month: 'short' }),
            disabled: !this.isMonthWithinBounds(new Date(this.displayYear, value, 1)),
        }));
    },
    get yearOptions() {
        const current = new Date().getFullYear();
        const first = this.parseDate(this.startMonth)?.getFullYear() ?? current - 100;
        const last = this.parseDate(this.endMonth)?.getFullYear() ?? current + 100;

        return Array.from({ length: Math.max(0, last - first + 1) }, (_, index) => first + index);
    },
    get weekdays() {
        const sunday = new Date(2024, 0, 7);

        return Array.from({ length: 7 }, (_, index) => {
            const date = new Date(sunday);
            date.setDate(sunday.getDate() + index);

            return {
                short: date.toLocaleDateString(undefined, { weekday: 'short' }),
                long: date.toLocaleDateString(undefined, { weekday: 'long' }),
            };
        });
    },
    get weeks() {
        if (!this.month) {
            return [];
        }

        const first = new Date(this.month.getFullYear(), this.month.getMonth(), 1);
        const start = new Date(first);
        start.setDate(first.getDate() - first.getDay());

        return Array.from({ length: 6 }, (_, week) => Array.from({ length: 7 }, (_, weekday) => {
            const date = new Date(start);
            date.setDate(start.getDate() + week * 7 + weekday);
            const key = this.dateKey(date);

            return {
                date: key,
                label: date.getDate(),
                outside: date.getMonth() !== this.month.getMonth(),
                today: key === this.dateKey(new Date()),
                disabled: (this.min && key < this.min) || (this.max && key > this.max),
            };
        }));
    },
    isSelected(date) {
        if (this.mode === 'multiple') {
            return Array.isArray(this.value) && this.value.includes(date);
        }

        return this.mode === 'single' && this.value === date;
    },
    isRangeStart(date) {
        return this.mode === 'range' && this.value?.from === date;
    },
    isRangeEnd(date) {
        return this.mode === 'range' && this.value?.to === date;
    },
    isRangeMiddle(date) {
        return this.mode === 'range' && this.value?.from && this.value?.to && date > this.value.from && date < this.value.to;
    },
    select(date) {
        if (this.mode === 'multiple') {
            const values = Array.isArray(this.value) ? this.value : [];
            this.value = values.includes(date) ? values.filter((item) => item !== date) : [...values, date];
        } else if (this.mode === 'range') {
            this.value = !this.value?.from || this.value?.to || date < this.value.from
                ? { from: date, to: null }
                : { from: this.value.from, to: date };
        } else {
            this.value = date;
        }

        this.focusedDate = date;
        this.$dispatch('change', this.value);
    },
    focusDay(date) {
        this.focusedDate = date;
        this.dayFocused = true;
    },
    blurDay() {
        this.dayFocused = false;
    },
    isMonthWithinBounds(date) {
        const key = this.dateKey(new Date(date.getFullYear(), date.getMonth(), 1)).slice(0, 7);

        return (!this.startMonth || key >= String(this.startMonth).slice(0, 7)) &&
            (!this.endMonth || key <= String(this.endMonth).slice(0, 7));
    },
    setMonth(month) {
        const next = new Date(this.displayYear, Number(month), 1);

        if (this.isMonthWithinBounds(next)) {
            this.month = next;
            this.focusedDate = this.dateKey(next);
        }
    },
    setYear(year) {
        const next = new Date(Number(year), this.displayMonth, 1);

        if (!this.isMonthWithinBounds(next)) {
            const boundary = Number(year) === this.parseDate(this.startMonth)?.getFullYear() ? this.startMonth : this.endMonth;
            this.month = this.parseDate(boundary);
        } else {
            this.month = next;
        }

        this.focusedDate = this.dateKey(this.month);
    },
    moveMonth(offset) {
        const next = new Date(this.month.getFullYear(), this.month.getMonth() + offset, 1);

        if (!this.isMonthWithinBounds(next)) {
            return;
        }

        this.month = next;
        this.focusedDate = this.dateKey(this.month);
        this.$nextTick(() => this.$root.querySelector(`[data-date="${this.focusedDate}"]`)?.focus());
    },
    focusDate(date) {
        const next = this.parseDate(date);

        this.focusedDate = date;
        if (next.getMonth() !== this.month.getMonth() || next.getFullYear() !== this.month.getFullYear()) {
            this.month = new Date(next.getFullYear(), next.getMonth(), 1);
        }
        this.$nextTick(() => this.$root.querySelector(`[data-date="${date}"]`)?.focus());
    },
    handleDayKeydown(event, date) {
        const offsets = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };

        if (offsets[event.key] !== undefined) {
            event.preventDefault();
            const next = this.parseDate(date);
            next.setDate(next.getDate() + offsets[event.key]);
            this.focusDate(this.dateKey(next));
        } else if (event.key === 'Home' || event.key === 'End') {
            event.preventDefault();
            const next = this.parseDate(date);
            next.setDate(next.getDate() + (event.key === 'Home' ? -next.getDay() : 6 - next.getDay()));
            this.focusDate(this.dateKey(next));
        } else if (event.key === 'PageUp' || event.key === 'PageDown') {
            event.preventDefault();
            this.moveMonth(event.key === 'PageUp' ? -1 : 1);
        }
    },
});

const inputOtp = (value = '', maxLength = 6) => ({
    value: String(value ?? '').slice(0, maxLength),
    maxLength: Number(maxLength),
    focused: false,
    selection: 0,
    get activeIndex() {
        return Math.min(this.selection, this.maxLength - 1);
    },
    character(index) {
        return this.value[index] ?? '';
    },
    isActive(index) {
        return this.focused && this.activeIndex === index;
    },
    hasFakeCaret(index) {
        return this.isActive(index) && !this.character(index);
    },
    update(event) {
        this.value = event.currentTarget.value.slice(0, this.maxLength);
        this.selection = event.currentTarget.selectionStart ?? this.value.length;

        if (this.value.length === this.maxLength) {
            this.$dispatch('complete', this.value);
        }
    },
    updateSelection(event) {
        this.selection = event.currentTarget.selectionStart ?? this.value.length;
    },
    focusAt(index) {
        const position = Math.min(index, this.value.length);

        this.$refs.input.focus();
        this.$refs.input.setSelectionRange(position, position);
        this.selection = position;
    },
});

const messageScroller = () => ({
    viewport: null,
    resizeObserver: null,
    mutationObserver: null,
    atStart: true,
    atEnd: true,
    initViewport(viewport) {
        this.viewport = viewport;
        this.$nextTick(() => this.scrollToEdge('end', 'auto'));
        this.resizeObserver = new ResizeObserver(() => this.updateScrollState());
        this.resizeObserver.observe(viewport);
        this.mutationObserver = new MutationObserver(() => {
            const shouldScroll = this.atEnd;

            this.$nextTick(() => {
                if (shouldScroll) {
                    this.scrollToEdge('end', 'auto');
                }
                this.updateScrollState();
            });
        });
        this.mutationObserver.observe(viewport, { childList: true, subtree: true, characterData: true });
    },
    updateScrollState() {
        if (!this.viewport) {
            return;
        }

        const maximum = this.viewport.scrollHeight - this.viewport.clientHeight;
        this.atStart = this.viewport.scrollTop <= 1;
        this.atEnd = maximum <= 1 || this.viewport.scrollTop >= maximum - 1;
    },
    scrollToEdge(direction, behavior = 'smooth') {
        if (!this.viewport) {
            return;
        }

        const top = direction === 'start' ? 0 : this.viewport.scrollHeight - this.viewport.clientHeight;

        this.viewport.scrollTo({ top, behavior });
        requestAnimationFrame(() => this.updateScrollState());
    },
    isButtonActive(direction) {
        return direction === 'start' ? !this.atStart : !this.atEnd;
    },
});

const scrollArea = () => ({
    root: null,
    viewport: null,
    resizeObserver: null,
    mutationObserver: null,
    initialize(root) {
        this.root = root;
        this.viewport = root.querySelector('[data-slot="scroll-area-viewport"]');
        this.$nextTick(() => this.update());
        this.resizeObserver = new ResizeObserver(() => this.update());
        this.resizeObserver.observe(this.viewport);
        this.mutationObserver = new MutationObserver(() => this.$nextTick(() => this.update()));
        this.mutationObserver.observe(this.viewport, { childList: true, subtree: true, characterData: true });
    },
    update() {
        if (!this.viewport) {
            return;
        }

        this.updateThumb('vertical', this.viewport.clientHeight, this.viewport.scrollHeight, this.viewport.scrollTop);
        this.updateThumb('horizontal', this.viewport.clientWidth, this.viewport.scrollWidth, this.viewport.scrollLeft);
    },
    updateThumb(orientation, viewportSize, scrollSize, scrollPosition) {
        const scrollbar = this.root.querySelector(`[data-orientation="${orientation}"]`);
        const thumb = scrollbar?.querySelector('[data-slot="scroll-area-thumb"]');

        if (!scrollbar || !thumb) {
            return;
        }

        const trackSize = orientation === 'vertical' ? scrollbar.clientHeight : scrollbar.clientWidth;
        const thumbSize = scrollSize > 0 ? Math.max(18, trackSize * viewportSize / scrollSize) : trackSize;
        const travel = Math.max(0, trackSize - thumbSize);
        const maximum = Math.max(0, scrollSize - viewportSize);
        const offset = maximum > 0 ? travel * scrollPosition / maximum : 0;

        scrollbar.hidden = maximum <= 1;
        thumb.style[orientation === 'vertical' ? 'height' : 'width'] = `${thumbSize}px`;
        thumb.style.transform = orientation === 'vertical'
            ? `translateY(${offset}px)`
            : `translateX(${offset}px)`;
    },
    startDrag(event, orientation) {
        event.preventDefault();
        const scrollbar = event.currentTarget;
        const thumb = scrollbar.querySelector('[data-slot="scroll-area-thumb"]');
        const startPointer = orientation === 'vertical' ? event.clientY : event.clientX;
        const startScroll = orientation === 'vertical' ? this.viewport.scrollTop : this.viewport.scrollLeft;
        const trackSize = orientation === 'vertical' ? scrollbar.clientHeight : scrollbar.clientWidth;
        const thumbSize = orientation === 'vertical' ? thumb.clientHeight : thumb.clientWidth;
        const maximum = orientation === 'vertical'
            ? this.viewport.scrollHeight - this.viewport.clientHeight
            : this.viewport.scrollWidth - this.viewport.clientWidth;
        const move = (moveEvent) => {
            const pointer = orientation === 'vertical' ? moveEvent.clientY : moveEvent.clientX;
            const next = startScroll + (pointer - startPointer) * maximum / Math.max(1, trackSize - thumbSize);

            if (orientation === 'vertical') {
                this.viewport.scrollTop = next;
            } else {
                this.viewport.scrollLeft = next;
            }
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
    },
});

const resizable = (orientation = 'horizontal') => ({
    orientation,
    root: null,
    initialize(root) {
        this.root = root;
        this.$nextTick(() => this.normalizePanels());
    },
    panels() {
        return [...this.root.querySelectorAll(':scope > [data-slot="resizable-panel"]')];
    },
    normalizePanels() {
        const panels = this.panels();
        const unspecified = panels.filter((panel) => !panel.dataset.defaultSize);
        const specified = panels.reduce((total, panel) => total + Number(panel.dataset.defaultSize || 0), 0);
        const fallback = Math.max(0, 100 - specified) / Math.max(1, unspecified.length);

        panels.forEach((panel) => {
            const size = Number(panel.dataset.defaultSize || fallback || (100 / panels.length));
            panel.style.flexBasis = `${size}%`;
        });
        this.updateHandles();
    },
    adjacentPanels(handle) {
        return [handle.previousElementSibling, handle.nextElementSibling];
    },
    limits(panel) {
        return {
            min: Number(panel.dataset.minSize || 0),
            max: Number(panel.dataset.maxSize || 100),
        };
    },
    resize(handle, delta) {
        const [before, after] = this.adjacentPanels(handle);

        if (!before || !after) {
            return;
        }

        const beforeSize = parseFloat(before.style.flexBasis);
        const afterSize = parseFloat(after.style.flexBasis);
        const beforeLimits = this.limits(before);
        const afterLimits = this.limits(after);
        const minimumDelta = Math.max(beforeLimits.min - beforeSize, afterSize - afterLimits.max);
        const maximumDelta = Math.min(beforeLimits.max - beforeSize, afterSize - afterLimits.min);
        const constrained = Math.max(minimumDelta, Math.min(delta, maximumDelta));

        before.style.flexBasis = `${beforeSize + constrained}%`;
        after.style.flexBasis = `${afterSize - constrained}%`;
        this.updateHandles();
    },
    startResize(event) {
        event.preventDefault();
        const handle = event.currentTarget;
        const rect = this.root.getBoundingClientRect();
        const size = this.orientation === 'vertical' ? rect.height : rect.width;
        let previous = this.orientation === 'vertical' ? event.clientY : event.clientX;
        const move = (moveEvent) => {
            const current = this.orientation === 'vertical' ? moveEvent.clientY : moveEvent.clientX;
            this.resize(handle, (current - previous) / size * 100);
            previous = current;
        };
        const stop = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', stop);
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', stop, { once: true });
    },
    resizeWithKeyboard(event) {
        const directions = this.orientation === 'vertical'
            ? { ArrowUp: -2, ArrowDown: 2 }
            : { ArrowLeft: -2, ArrowRight: 2 };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.resize(event.currentTarget, directions[event.key]);
        }
    },
    updateHandles() {
        this.root.querySelectorAll(':scope > [data-slot="resizable-handle"]').forEach((handle) => {
            const before = handle.previousElementSibling;
            handle.setAttribute('aria-valuenow', String(Math.round(parseFloat(before?.style.flexBasis || '0'))));
        });
    },
});

const carousel = (orientation = 'horizontal') => ({
    orientation,
    viewport: null,
    resizeObserver: null,
    canScrollPrevious: false,
    canScrollNext: false,
    initialize(viewport) {
        this.viewport = viewport;
        this.resizeObserver = new ResizeObserver(() => this.update());
        this.resizeObserver.observe(viewport);
        this.$nextTick(() => this.update());
    },
    update() {
        if (!this.viewport) {
            return;
        }

        const position = this.orientation === 'vertical' ? this.viewport.scrollTop : this.viewport.scrollLeft;
        const viewportSize = this.orientation === 'vertical' ? this.viewport.clientHeight : this.viewport.clientWidth;
        const scrollSize = this.orientation === 'vertical' ? this.viewport.scrollHeight : this.viewport.scrollWidth;

        this.canScrollPrevious = position > 1;
        this.canScrollNext = position < scrollSize - viewportSize - 1;
    },
    scroll(direction) {
        const item = this.viewport?.querySelector('[data-slot="carousel-item"]');

        if (!item) {
            return;
        }

        this.viewport.scrollBy({
            [this.orientation === 'vertical' ? 'top' : 'left']: direction * (this.orientation === 'vertical' ? item.offsetHeight : item.offsetWidth),
            behavior: 'smooth',
        });
    },
    scrollPrevious() {
        this.scroll(-1);
    },
    scrollNext() {
        this.scroll(1);
    },
    handleKeydown(event) {
        const directions = this.orientation === 'vertical'
            ? { ArrowUp: -1, ArrowDown: 1 }
            : { ArrowLeft: -1, ArrowRight: 1 };

        if (directions[event.key] !== undefined) {
            event.preventDefault();
            this.scroll(directions[event.key]);
        }
    },
});

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

const menuItems = (menu) => [...menu.querySelectorAll('[role^="menuitem"]')]
    .filter((item) => item.closest('[role="menu"]') === menu && !item.hasAttribute('data-disabled'));

const menuNavigation = {
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

const menubarRadioGroup = (value = null) => ({
    selectedValue: value,
    selectValue(value) {
        this.selectedValue = value;
        this.$dispatch('change', value);
        this.closeMenu?.();
    },
});

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

const dropdownMenuCheckbox = (checked = false) => ({
    checked,
    toggleChecked() {
        this.checked = !this.checked;
        this.$dispatch('change', this.checked);
        this.closeMenu?.();
    },
});

const dropdownMenuRadioGroup = (value = null) => ({
    value,
    selectValue(value) {
        this.value = value;
        this.$dispatch('change', value);
        this.closeMenu?.();
    },
});

const dropdownMenuSub = () => ({
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

const toastStore = {
    toasts: [],
    nextId: 0,
    add(message, options = {}) {
        const isObject = message !== null && typeof message === 'object';
        const toast = {
            id: ++this.nextId,
            title: isObject ? message.title : message,
            description: isObject ? message.description : options.description,
            type: (isObject ? message.type : options.type) ?? 'default',
            action: (isObject ? message.action : options.action) ?? null,
            duration: Number((isObject ? message.duration : options.duration) ?? 4000),
            remaining: 0,
            startedAt: 0,
            timer: null,
        };

        toast.remaining = toast.duration;
        this.toasts.push(toast);
        this.startTimer(toast);

        return toast.id;
    },
    dismiss(id = null) {
        const removed = id === null ? this.toasts : this.toasts.filter((toast) => toast.id === id);

        removed.forEach((toast) => clearTimeout(toast.timer));
        this.toasts = id === null ? [] : this.toasts.filter((toast) => toast.id !== id);
    },
    pause() {
        this.toasts.forEach((toast) => {
            if (!toast.timer) {
                return;
            }

            clearTimeout(toast.timer);
            toast.timer = null;
            toast.remaining = Math.max(0, toast.remaining - (Date.now() - toast.startedAt));
        });
    },
    resume() {
        this.toasts.forEach((toast) => this.startTimer(toast));
    },
    startTimer(toast) {
        if (!Number.isFinite(toast.remaining) || toast.remaining <= 0) {
            return;
        }

        toast.startedAt = Date.now();
        toast.timer = setTimeout(() => this.dismiss(toast.id), toast.remaining);
    },
};

const sonner = (position = 'bottom-right', expand = false, visibleToasts = 3) => ({
    position,
    expanded: expand,
    visibleToasts: Number(visibleToasts),
    heights: {},
    get toasts() {
        return [...this.$store.toast.toasts].reverse();
    },
    registerToast(toast, element) {
        const update = () => {
            this.heights = { ...this.heights, [toast.id]: element.offsetHeight };
        };

        update();
        new ResizeObserver(update).observe(element);
    },
    offset(index) {
        return this.toasts.slice(0, index).reduce((offset, toast) => offset + (this.heights[toast.id] ?? 76) + 12, 0);
    },
    toastStyle(index) {
        const distance = this.expanded ? this.offset(index) : index * 16;
        const edge = this.position.startsWith('top') ? 'top' : 'bottom';
        const scale = this.expanded ? 1 : Math.max(0.8, 1 - index * 0.05);

        return {
            [edge]: `${distance}px`,
            opacity: index < this.visibleToasts ? 1 : 0,
            pointerEvents: index < this.visibleToasts ? 'auto' : 'none',
            transform: `scale(${scale})`,
            zIndex: this.toasts.length - index,
        };
    },
    viewportStyle() {
        if (!this.toasts.length) {
            return { height: '0px' };
        }

        const visible = Math.min(this.toasts.length, this.visibleToasts);
        const height = this.expanded
            ? this.offset(visible - 1) + (this.heights[this.toasts[visible - 1]?.id] ?? 76)
            : (this.heights[this.toasts[0]?.id] ?? 76) + Math.max(0, visible - 1) * 16;

        return { height: `${height}px` };
    },
    runAction(toast) {
        toast.action?.onClick?.();
        this.$store.toast.dismiss(toast.id);
    },
});

const sidebar = (open = true) => ({
    open,
    openMobile: false,
    isMobile: false,
    media: null,
    get state() {
        return this.open ? 'expanded' : 'collapsed';
    },
    init() {
        const saved = document.cookie.split('; ')
            .find((cookie) => cookie.startsWith('sidebar_state='))
            ?.split('=')[1];

        if (saved === 'true' || saved === 'false') {
            this.open = saved === 'true';
        }

        this.media = window.matchMedia('(max-width: 767px)');
        this.isMobile = this.media.matches;
        this.media.addEventListener('change', (event) => {
            this.isMobile = event.matches;
            if (!this.isMobile) {
                this.openMobile = false;
            }
        });
    },
    setOpen(open) {
        this.open = open;
        document.cookie = `sidebar_state=${open}; path=/; max-age=604800; SameSite=Lax`;
        this.$dispatch('sidebar-change', open);
    },
    toggleSidebar() {
        if (this.isMobile) {
            this.openMobile = !this.openMobile;
            return;
        }

        this.setOpen(!this.open);
    },
    closeMobile() {
        this.openMobile = false;
    },
    sidebarStyle(side, collapsible) {
        const hidden = side === 'left' ? 'translateX(-100%)' : 'translateX(100%)';

        if (this.isMobile) {
            return { transform: this.openMobile ? 'translateX(0)' : hidden };
        }

        return {
            transform: !this.open && collapsible === 'offcanvas' ? hidden : 'translateX(0)',
        };
    },
    handleShortcut(event) {
        if (event.key.toLocaleLowerCase() === 'b' && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            this.toggleSidebar();
        }
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
    Alpine.store('toast', toastStore);

    // Data.
    Alpine.data('uiAccordion', accordion);
    Alpine.data('uiCarousel', carousel);
    Alpine.data('uiCalendar', calendar);
    Alpine.data('uiCollapsible', disclosure);
    Alpine.data('uiCommand', command);
    Alpine.data('uiCombobox', combobox);
    Alpine.data('uiTabs', tabs);
    Alpine.data('uiToggle', toggle);
    Alpine.data('uiToggleGroup', toggleGroup);
    Alpine.data('uiAvatar', () => ({ imageLoaded: false }));
    Alpine.data('uiCheckbox', disclosure);
    Alpine.data('uiContextMenu', contextMenu);
    Alpine.data('uiContextMenuCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiContextMenuRadioGroup', dropdownMenuRadioGroup);
    Alpine.data('uiContextMenuSub', dropdownMenuSub);
    Alpine.data('uiDialog', dialog);
    Alpine.data('uiDrawer', drawer);
    Alpine.data('uiDropdownMenu', dropdownMenu);
    Alpine.data('uiDropdownMenuCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiDropdownMenuRadioGroup', dropdownMenuRadioGroup);
    Alpine.data('uiDropdownMenuSub', dropdownMenuSub);
    Alpine.data('uiMenubar', menubar);
    Alpine.data('uiMenubarCheckbox', dropdownMenuCheckbox);
    Alpine.data('uiMenubarRadioGroup', menubarRadioGroup);
    Alpine.data('uiMenubarSub', dropdownMenuSub);
    Alpine.data('uiHoverCard', hoverCard);
    Alpine.data('uiPopover', popover);
    Alpine.data('uiRadioGroup', (value = null) => ({ value }));
    Alpine.data('uiSelect', select);
    Alpine.data('uiSlider', slider);
    Alpine.data('uiSwitch', disclosure);
    Alpine.data('uiTooltip', tooltip);
    Alpine.data('uiInputOtp', inputOtp);
    Alpine.data('uiMessageScroller', messageScroller);
    Alpine.data('uiNavigationMenu', navigationMenu);
    Alpine.data('uiResizable', resizable);
    Alpine.data('uiScrollArea', scrollArea);
    Alpine.data('uiSidebar', sidebar);
    Alpine.data('uiSonner', sonner);

    // Directives.

    // Magic.
    Alpine.magic('toast', () => {
        const notify = (message, options = {}) => Alpine.store('toast').add(message, options);

        ['success', 'info', 'warning', 'error', 'loading'].forEach((type) => {
            notify[type] = (message, options = {}) => notify(message, { ...options, type });
        });
        notify.dismiss = (id = null) => Alpine.store('toast').dismiss(id);

        return notify;
    });

}
