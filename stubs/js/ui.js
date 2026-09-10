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
    Alpine.data('uiSlider', slider);
    Alpine.data('uiSwitch', disclosure);
    Alpine.data('uiTooltip', tooltip);
    Alpine.data('uiInputOtp', inputOtp);
    Alpine.data('uiMessageScroller', messageScroller);
    Alpine.data('uiResizable', resizable);
    Alpine.data('uiScrollArea', scrollArea);

    // Directives.

    // Magic.

}
