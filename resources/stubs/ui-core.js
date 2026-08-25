import anchor from '@alpinejs/anchor';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import {autoUpdate, computePosition, flip, offset as flOffset, shift, size} from "@floating-ui/dom";

const ANCHOR_PLACEMENTS = ['top', 'top-start', 'top-end', 'right', 'right-start', 'right-end', 'bottom', 'bottom-start', 'bottom-end', 'left', 'left-start', 'left-end'];

let _uiId = 0;

/**
 * Ensures that a DOM node has a unique ID. If the node does not have an ID, it generates one using the provided prefix
 * and a random string.
 */
function ensureId(node, prefix = 'ui') {
    if (!node.id) node.id = `${prefix}-${++_uiId}-${Math.random().toString(36).slice(2, 7)}`;
    return node.id;
}

/**
 * Find the genuine focusable control a trigger wrapper stands for. The wrapper is usually a `display:contents` span
 * whose first interactive descendant is the actual <button>/<a>; fall back to the element itself when it is focusable.
 */
function resolveControl(el) {
    const focusableSel = 'button, [href], input, select, textarea, [tabindex]';
    if (el.matches(focusableSel) && el.getAttribute('tabindex') !== '-1') return el;
    return (el.querySelector('button:not([tabindex="-1"]), a[href]:not([tabindex="-1"]), input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])') || el.firstElementChild || el);
}

/**
 * UI anchor directive.
 */
function uiAnchorDirective(el, {modifiers, expression}, {evaluateLater, cleanup}) {
    // Alpine preserves hyphens in modifier tokens (splits only on '.'), so
    // 'bottom-start' arrives as a single entry in the modifiers array.
    const placement = ANCHOR_PLACEMENTS.find((p) => modifiers.includes(p)) || 'bottom';

    // Main side offset (dot-modifier: .offset.4).
    let offsetValue = 0;
    if (modifiers.includes('offset')) {
        const i = modifiers.indexOf('offset');
        offsetValue = modifiers[i + 1] !== undefined ? Number(modifiers[i + 1]) : 0;
    }

    // Align offset supports negative values via data-align-offset attribute.
    const alignOffsetValue = el.dataset.alignOffset !== undefined ? Number(el.dataset.alignOffset) : 0;

    // avoidCollisions="false" disables flip + shift (data-avoid-collisions="false").
    const avoidCollisions = el.dataset.avoidCollisions !== 'false';
    const allowFlip = !modifiers.includes('no-flip') && avoidCollisions;

    const PAD = 8;
    const designMax = parseFloat(getComputedStyle(el).maxHeight) || Infinity;
    const getReference = evaluateLater(expression);

    let stop = null;
    // Defer until after x-init handlers on sibling elements have run (e.g. x-ref="trigger").
    queueMicrotask(() => {
        getReference((reference) => {
            if (!reference || stop) return;
            const update = () => {
                // Skip positioning when the element is closing (leave transition)
                // or the reference has no layout, to prevent flashing to 0,0.
                if (el.dataset.state === 'closed') return;
                const ref = typeof reference === 'function' ? reference() : reference;
                if (!ref) return;
                const rect = ref.getBoundingClientRect();
                if (rect.width === 0 && rect.height === 0) return;
                return computePosition(reference, el, {
                    strategy: 'fixed',
                    placement,
                    middleware: [
                        flOffset({mainAxis: offsetValue, alignmentAxis: alignOffsetValue}),
                        allowFlip && flip({padding: PAD}),
                        avoidCollisions && shift({padding: PAD}),
                        size({
                            padding: PAD,
                            apply({availableWidth, availableHeight, rects}) {
                                // Fit the available space but never exceed the design cap (min 140px so it
                                // never collapses). The popover carries overflow-y-auto, so it scrolls.
                                const h = Math.min(designMax, Math.max(140, Math.floor(availableHeight)));
                                el.style.maxHeight = Number.isFinite(h) ? `${h}px` : '';
                                // Match the trigger width as a minimum.
                                el.style.minWidth = `${rects.reference.width}px`;
                                // Radix-compatible CSS custom properties.
                                el.style.setProperty('--radix-select-trigger-width', `${rects.reference.width}px`);
                                el.style.setProperty('--radix-select-trigger-height', `${rects.reference.height}px`);
                                el.style.setProperty('--radix-select-content-available-width', `${Math.floor(availableWidth)}px`);
                                el.style.setProperty('--radix-select-content-available-height', `${Math.floor(availableHeight)}px`);
                            },
                        }),
                    ].filter(Boolean),
                }).then(({x, y, placement: resolvedPlacement}) => {
                    Object.assign(el.style, {position: 'fixed', left: `${x}px`, top: `${y}px`});
                    // Expose resolved side and align as data attributes for CSS consumers.
                    const [side, align = 'center'] = resolvedPlacement.split('-');
                    el.dataset.side = side;
                    el.dataset.align = align;
                });
            };
            stop = autoUpdate(reference, el, update);
        });
    });

    cleanup(() => stop && stop());
}

/**
 * UI Calendar data.
 */
const uiCalendarData = (cfg = {}) => ({
    calendarId: cfg.calendarId || null,
    mode: cfg.mode || 'single',
    locale: cfg.locale || undefined,
    todayLabel: cfg.todayLabel || 'Today, :date',
    selectedLabel: cfg.selectedLabel || 'selected',
    numberOfMonths: cfg.numberOfMonths || 1,
    weekStart: cfg.weekStart || 0,
    captionLayout: cfg.captionLayout || 'label',
    showWeekNumber: !!cfg.showWeekNumber,
    disableNavigation: !!cfg.disableNavigation,
    minDays: cfg.minDays ?? cfg.min ?? null,
    maxDays: cfg.maxDays ?? cfg.max ?? null,
    disabledCfg: cfg.disabled || null,
    minDate: cfg.minDate ? _parse(cfg.minDate) : null,
    maxDate: cfg.maxDate ? _parse(cfg.maxDate) : null,
    outOfRange: cfg.outOfRange || 'disable',
    modifiers: cfg.modifiers || {},
    modifiersClass: cfg.modifiersClass || {},
    startMonth: null,
    endMonth: null,
    view: null,
    weekdays: [],
    single: null,
    multiple: [],
    rangeFrom: null,
    rangeTo: null,
    hover: null,
    focusedDate: null,

    init() {
        this.startMonth = cfg.startMonth ? _parse(cfg.startMonth) : null;
        this.endMonth = cfg.endMonth ? _parse(cfg.endMonth) : null;

        if (this.mode === 'single') this.single = cfg.value ? _parse(cfg.value) : null;
        else if (this.mode === 'multiple') this.multiple = (cfg.value || []).map(_parse);
        else if (this.mode === 'range') {
            this.rangeFrom = cfg.value && cfg.value.from ? _parse(cfg.value.from) : null;
            this.rangeTo = cfg.value && cfg.value.to ? _parse(cfg.value.to) : null;
        }

        let base = null;
        if (cfg.defaultMonth) base = _parse(cfg.defaultMonth.length === 7 ? cfg.defaultMonth + '-01' : cfg.defaultMonth);
        else base = this.single || this.rangeFrom || (this.multiple && this.multiple[0]) || this.startMonth || new Date();
        this.view = new Date(base.getFullYear(), base.getMonth(), 1);

        this.focusedDate = this.single || this.rangeFrom || (this.multiple && this.multiple[0]) || new Date(base.getFullYear(), base.getMonth(), base.getDate());

        const ref = new Date(2023, 0, 1);
        for (let i = 0; i < 7; i++) {
            const d = new Date(ref);
            d.setDate(ref.getDate() + ((this.weekStart + i + 7 - ref.getDay()) % 7));
            this.weekdays.push(d.toLocaleString(this.locale, {weekday: 'narrow'}));
        }
        const mine = (e) => {
            const d = e.detail;
            const id = d && typeof d === 'object' && !(d instanceof Date) ? d.id : null;
            return !id || id === this.calendarId;
        };

        const payload = (e) => {
            const d = e.detail;
            return d && typeof d === 'object' && !(d instanceof Date) ? (d.date ?? d.month ?? null) : d;
        };
        const onToday = (e) => {
            if (this.mode !== 'single' || !mine(e)) return;
            if (this.setValue(_ymd(new Date()))) this.notify('today');
        };
        const onSet = (e) => {
            if (this.mode !== 'single' || !mine(e)) return;
            const t = _parse(payload(e));
            if (t && this.setValue(_ymd(t))) this.notify('set');
        };

        const onSetRange = (e) => {
            if (this.mode !== 'range' || !mine(e)) return;
            const d = e.detail || {};
            if (this.setValue({from: d.from ?? null, to: d.to ?? null})) this.notify('set-range');
        };

        const onGoto = (e) => {
            if (!mine(e)) return;
            const raw = payload(e);
            const m = _parse(typeof raw === 'string' && raw.length === 7 ? raw + '-01' : raw);
            if (m) this.view = new Date(m.getFullYear(), m.getMonth(), 1);
        };
        const onClear = (e) => {
            if (!mine(e)) return;
            if (this.setValue(this.mode === 'single' ? null : this.mode === 'multiple' ? [] : {from: null, to: null})) {
                this.notify('clear');
            }
        };
        this._hooks = {
            'calendar:today': onToday,
            'calendar:set': onSet,
            'calendar:set-range': onSetRange,
            'calendar:goto': onGoto,
            'calendar:clear': onClear,
        };
        this._rootEl = this.$root;
        for (const target of [window, this._rootEl]) {
            for (const name in this._hooks) target.addEventListener(name, this._hooks[name]);
        }
    },

    destroy() {
        if (!this._hooks) return;
        for (const target of [window, this._rootEl]) {
            for (const name in this._hooks) target.removeEventListener(name, this._hooks[name]);
        }
    },

    get value() {
        if (this.mode === 'single') return this.single ? _ymd(this.single) : null;
        if (this.mode === 'multiple') return this.multiple.map(_ymd);
        return {from: this.rangeFrom ? _ymd(this.rangeFrom) : null, to: this.rangeTo ? _ymd(this.rangeTo) : null};
    },
    set value(v) {
        if (this.setValue(v)) this.notify('value');
    },

    setValue(v) {
        if (this.mode === 'single') {
            const next = v ? _ymd(_parse(v)) : null;
            const changed = next !== (this.single ? _ymd(this.single) : null);
            this.single = next ? _parse(next) : null;
            if (this.single) this.reveal(this.single);
            return changed;
        }
        if (this.mode === 'multiple') {
            const list = (Array.isArray(v) ? v : v ? [v] : []).filter(Boolean).map(_parse);
            const changed = list.map(_ymd).join(',') !== this.multiple.map(_ymd).join(',');
            this.multiple = list;
            if (list[0]) this.reveal(list[0]);
            return changed;
        }
        const d = v || {};
        const from = d.from ? _parse(d.from) : null;
        const to = d.to ? _parse(d.to) : null;
        const key = (a, b) => this.fmt(a) + '/' + this.fmt(b);
        const changed = key(from, to) !== key(this.rangeFrom, this.rangeTo);
        this.rangeFrom = from;
        this.rangeTo = to;
        this.hover = null;
        if (from) this.reveal(from);
        return changed;
    },
    reveal(d) {
        if (!d) return;
        if (!this._viewContains(d)) this.view = new Date(d.getFullYear(), d.getMonth(), 1);
        this.focusedDate = d;
    },


    get months() {
        return Array.from({length: this.numberOfMonths}, (_, i) => _addMonths(this.view, i));
    },
    monthLabel(m) {
        return m.toLocaleString(this.locale, {month: 'long', year: 'numeric'});
    },
    weeksFor(m) {
        const year = m.getFullYear(), month = m.getMonth();
        const first = new Date(year, month, 1);
        const offset = (first.getDay() - this.weekStart + 7) % 7;
        const start = new Date(year, month, 1 - offset);
        const weeks = [];
        for (let w = 0; w < 6; w++) {
            const days = [];
            for (let d = 0; d < 7; d++) {
                const day = new Date(start);
                day.setDate(start.getDate() + w * 7 + d);
                day.__outside = day.getMonth() !== month;
                days.push(day);
            }
            weeks.push(days);
        }
        return weeks;
    },
    weekNumber(week) {
        const d = new Date(week[0]);
        d.setDate(d.getDate() + 3 - ((d.getDay() + 6) % 7));
        const firstThu = new Date(d.getFullYear(), 0, 4);
        return 1 + Math.round(((d - firstThu) / 86400000 - 3 + ((firstThu.getDay() + 6) % 7)) / 7);
    },

    isOutside(d, m) {
        return d.getMonth() !== m.getMonth();
    },
    isToday(d) {
        return _sameDay(d, new Date());
    },
    isOutOfRange(d) {
        return !!((this.minDate && d < this.minDate) || (this.maxDate && d > this.maxDate));
    },
    isDisabled(d) {

        if (this.outOfRange !== 'flag' && this.isOutOfRange(d)) return true;
        if (this.startMonth && d < new Date(this.startMonth.getFullYear(), this.startMonth.getMonth(), 1)) return true;
        if (this.endMonth && d > new Date(this.endMonth.getFullYear(), this.endMonth.getMonth() + 1, 0)) return true;
        const c = this.disabledCfg;
        if (!c) return false;
        if (Array.isArray(c)) return c.some((x) => _sameDay(_parse(x), d));
        if (typeof c === 'object') {
            if (c.before && d < _parse(c.before)) return true;
            if (c.after && d > _parse(c.after)) return true;
            if (c.dayOfWeek && c.dayOfWeek.includes(d.getDay())) return true;
        }
        return false;
    },
    isSelected(d) {
        if (this.mode === 'single') return _sameDay(this.single, d);
        if (this.mode === 'multiple') return this.multiple.some((x) => _sameDay(x, d));
        return this.rangeIs(d).selected;
    },
    rangeIs(d) {
        const from = this.rangeFrom, to = this.rangeTo || (this.rangeFrom && this.hover);
        if (!from) return {};
        const lo = to && to < from ? to : from;
        const hi = to && to < from ? from : to;
        const isStart = _sameDay(d, lo);
        const isEnd = hi ? _sameDay(d, hi) : isStart;
        const inMid = hi && d > lo && d < hi;
        return {selected: isStart || isEnd || inMid, start: isStart, end: isEnd, middle: inMid};
    },
    modifierClass(d) {
        let cls = '';
        for (const name in this.modifiers) {
            const list = this.modifiers[name] || [];
            if (list.some((x) => _sameDay(_parse(x), d))) cls += ' ' + (this.modifiersClass[name] || '');
        }
        return cls;
    },

    select(d) {
        if (this.isDisabled(d)) return;
        if (this.mode === 'single') {
            this.single = _sameDay(this.single, d) && !cfg.required ? null : d;
        } else if (this.mode === 'multiple') {
            const i = this.multiple.findIndex((x) => _sameDay(x, d));
            if (i >= 0) this.multiple.splice(i, 1);
            else if (!this.maxDays || this.multiple.length < this.maxDays) this.multiple.push(d);
        } else {
            if (!this.rangeFrom || (this.rangeFrom && this.rangeTo)) {
                this.rangeFrom = d;
                this.rangeTo = null;
            } else {
                let from = this.rangeFrom, to = d;
                if (to < from) [from, to] = [to, from];
                const span = Math.round((to - from) / 86400000) + 1;
                if (this.minDays && span < this.minDays) {
                    this.rangeFrom = d;
                    this.rangeTo = null;
                } else if (this.maxDays && span > this.maxDays) {
                    this.rangeFrom = d;
                    this.rangeTo = null;
                } else {
                    this.rangeFrom = from;
                    this.rangeTo = to;
                }
            }
        }
        this.notify('select');
    },
    notify(source) {
        const value = this.value;
        this.$dispatch('calendar:updated', {id: this.calendarId, mode: this.mode, value, source});
        if (source === 'select') this.$dispatch('calendar-change', value);
    },
    emit(value) {
        this.$dispatch('calendar-change', value);
    },

    get canPrev() {
        if (this.disableNavigation) return false;
        if (!this.startMonth) return true;
        return _addMonths(this.view, -1) >= new Date(this.startMonth.getFullYear(), this.startMonth.getMonth(), 1);
    },
    get canNext() {
        if (this.disableNavigation) return false;
        if (!this.endMonth) return true;
        return _addMonths(this.view, this.numberOfMonths) <= new Date(this.endMonth.getFullYear(), this.endMonth.getMonth(), 1);
    },
    prev() {
        if (!this.canPrev) return;
        this.view = _addMonths(this.view, -1);
        this.focusedDate = new Date(this.focusedDate.getFullYear(), this.focusedDate.getMonth() - 1, this.focusedDate.getDate());
    },
    next() {
        if (!this.canNext) return;
        this.view = _addMonths(this.view, 1);
        this.focusedDate = new Date(this.focusedDate.getFullYear(), this.focusedDate.getMonth() + 1, this.focusedDate.getDate());
    },

    isFocused(d) {
        return _sameDay(d, this.focusedDate);
    },
    dayLabel(d) {
        const base = d.toLocaleDateString(this.locale, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        let label = this.isToday(d) ? this.todayLabel.replace(':date', base) : base;
        if (this.isSelected(d)) label += ', ' + this.selectedLabel;
        return label;
    },
    _viewContains(d) {
        const start = new Date(this.view.getFullYear(), this.view.getMonth(), 1);
        const end = new Date(this.view.getFullYear(), this.view.getMonth() + this.numberOfMonths, 0);
        return d >= start && d <= end;
    },
    _focus(d) {
        this.focusedDate = d;
        if (!this._viewContains(d)) this.view = new Date(d.getFullYear(), d.getMonth(), 1);
        const key = _ymd(d);
        setTimeout(() => {
            const el = this.$root.querySelector('[data-day="' + key + '"]');
            if (el) el.focus();
        }, 0);
    },
    moveFocus(days) {
        const d = new Date(this.focusedDate);
        d.setDate(d.getDate() + days);
        this._focus(d);
    },
    moveFocusMonths(n) {
        const d = new Date(this.focusedDate);
        d.setMonth(d.getMonth() + n);
        this._focus(d);
    },
    focusWeekEdge(end) {
        const d = new Date(this.focusedDate);
        const offset = (d.getDay() - this.weekStart + 7) % 7;
        d.setDate(d.getDate() + (end ? 6 - offset : -offset));
        this._focus(d);
    },
    isRtl() {
        const el = this._rootEl;
        return !!(el && el.nodeType === 1 && typeof getComputedStyle === 'function' && getComputedStyle(el).direction === 'rtl');
    },
    onDayKeydown(e, d) {
        const k = e.key;
        const step = this.isRtl() ? -1 : 1;
        if (k === 'ArrowLeft') this.moveFocus(-step);
        else if (k === 'ArrowRight') this.moveFocus(step);
        else if (k === 'ArrowUp') this.moveFocus(-7);
        else if (k === 'ArrowDown') this.moveFocus(7);
        else if (k === 'Home') this.focusWeekEdge(false);
        else if (k === 'End') this.focusWeekEdge(true);
        else if (k === 'PageUp') this.moveFocusMonths(-1);
        else if (k === 'PageDown') this.moveFocusMonths(1);
        else if (k === 'Enter' || k === ' ') {
            this.select(d);
            this.focusedDate = d;
        } else return;
        e.preventDefault();
    },

    get years() {
        const start = this.startMonth ? this.startMonth.getFullYear() : new Date().getFullYear() - 100;
        const end = this.endMonth ? this.endMonth.getFullYear() : new Date().getFullYear() + 10;
        return Array.from({length: end - start + 1}, (_, i) => start + i);
    },
    get monthNames() {
        return Array.from({length: 12}, (_, i) => new Date(2023, i, 1).toLocaleString(this.locale, {month: 'short'}));
    },
    setMonth(m) {
        this.view = new Date(this.view.getFullYear(), Number(m), 1);
    },
    setYear(y) {
        this.view = new Date(Number(y), this.view.getMonth(), 1);
    },

    fmt(d) {
        return d ? _ymd(d) : '';
    },
    get multipleValue() {
        return this.multiple.map(_ymd).join(',');
    },
});

/**
 * Dialog layer directive.
 */
function uiDialogLayerDirective(el) {
    queueMicrotask(() => {
        const home = el._x_teleportBack;
        if (!home) return;
        const target = home.closest('dialog') || document.body;
        if (el.parentElement !== target) {
            target.appendChild(el);
            requestAnimationFrame(() => window.dispatchEvent(new Event('resize')));
        }
    });
}

/**
 * Item-aligned positioning directive.
 *
 * Positions the floating element so the selected item (or the first item when
 * nothing is selected) sits exactly over the trigger — matching the native
 * macOS select behaviour used by Radix when position="item-aligned".
 *
 * Usage: x-ui-item-aligned="$refs.trigger"
 */
function uiItemAlignedDirective(el, {expression}, {evaluateLater, effect, cleanup}) {
    const PAD = 8;
    const getReference = evaluateLater(expression);
    const getOpen = evaluateLater('open');

    const position = (trigger) => {
        const triggerRect = trigger.getBoundingClientRect();

        // Find the selected item, falling back to the first option.
        const selected = el.querySelector('[role="option"][aria-selected="true"]')
            || el.querySelector('[role="option"]:not([aria-disabled="true"])');

        // Place off-screen but measurable while computing layout.
        Object.assign(el.style, {
            position: 'fixed',
            visibility: 'hidden',
            left: '0px',
            top: '0px',
            minWidth: `${triggerRect.width}px`
        });

        requestAnimationFrame(() => {
            const elRect = el.getBoundingClientRect();
            const itemRect = selected ? selected.getBoundingClientRect() : {top: elRect.top};

            // How far down the item is from the top of the dropdown.
            const itemOffsetFromTop = itemRect.top - elRect.top;

            // Position the dropdown so the selected item sits over the trigger.
            let top = triggerRect.top - itemOffsetFromTop;

            // Clamp to viewport with padding.
            top = Math.max(PAD, Math.min(top, window.innerHeight - elRect.height - PAD));

            // Left-align with the trigger, clamped to viewport.
            let left = triggerRect.left;
            left = Math.max(PAD, Math.min(left, window.innerWidth - elRect.width - PAD));

            Object.assign(el.style, {top: `${top}px`, left: `${left}px`, visibility: ''});

            // Scroll so the selected item aligns with the trigger vertically.
            if (selected) {
                const drift = selected.getBoundingClientRect().top - triggerRect.top;
                if (Math.abs(drift) > 1) el.scrollTop += drift;
            }

            el.dataset.side = 'bottom';
            el.dataset.align = 'start';
        });
    };

    // Re-run positioning each time the select opens, after Alpine has finished
    // rendering the items (two rAF ticks: one for x-show display, one for layout).
    effect(() => {
        getOpen((isOpen) => {
            if (!isOpen) return;
            requestAnimationFrame(() => {
                getReference((trigger) => {
                    if (trigger) position(trigger);
                });
            });
        });
    });

    cleanup(() => {
    });
}

/**
 * Field directive.
 */
function uiFieldDirective(el) {
    queueMicrotask(() => {
        const control = el.querySelector(
            'input:not([type=hidden]), textarea, select, [role="checkbox"], [role="switch"], [role="radiogroup"], [role="combobox"], [role="slider"], [role="spinbutton"]',
        );
        if (!control) return;
        const ids = [];
        const desc = el.querySelector('[data-slot="field-description"]');
        const err = el.querySelector('[data-slot="field-error"]');
        if (desc) ids.push(ensureId(desc, 'field-desc'));
        if (err) ids.push(ensureId(err, 'field-err'));
        if (ids.length) {
            const prev = control.getAttribute('aria-describedby');
            control.setAttribute('aria-describedby', [prev, ...ids].filter(Boolean).join(' '));
        }
        if (err) {
            control.setAttribute('aria-invalid', 'true');
            el.setAttribute('data-invalid', 'true');
        }
        const label = el.querySelector('[data-slot="field-label"]');
        if (label && !label.getAttribute('for')) {
            label.setAttribute('for', ensureId(control, 'field-control'));
        }
    });
}

/**
 * Labelled by directive.
 */
function uiLabelledByDirective(el, {expression}, {evaluate}) {
    const cfg = expression ? evaluate(expression) : {};
    const wire = (sel, attr) => {
        if (!sel) return;
        const node = el.querySelector(sel);
        if (node) el.setAttribute(attr, ensureId(node, 'ui-label'));
    };

    queueMicrotask(() => {
        wire(cfg.label, 'aria-labelledby');
        wire(cfg.description, 'aria-describedby');
    });
}

/**
 * Listbox data.
 */
const uiListboxData = (config = {}) => ({
    trigger: config.trigger || 'button',
    open: false,
    filtering: false,
    multiple: !!config.multiple,
    value: config.value ?? (config.multiple ? [] : ''),
    activeValue: null,
    options: config.options || [],

    query: config.query ?? '',

    isSelected(v) {
        return this.multiple ? this.value.includes(v) : this.value === v;
    },
    get selected() {
        return this.options.filter((o) => this.isSelected(o.value));
    },

    get label() {
        const o = this.options.find((o) => o.value === this.value);
        return o ? o.label : '';
    },
    matches(label) {
        return label.toLowerCase().includes(this.query.toLowerCase());
    },
    get visible() {
        if (this.trigger === 'input' && !this.filtering) return this.options;
        return this.options.filter((o) => this.matches(o.label));
    },
    get visibleCount() {
        return this.visible.length;
    },
    ensureActive() {
        const v = this.visible;
        if (!v.length) {
            this.activeValue = null;
            return;
        }
        if (!v.some((o) => o.value === this.activeValue)) {
            this.activeValue = (v.find((o) => this.isSelected(o.value)) || v[0]).value;
        }
    },
    move(dir) {
        if (this.trigger === 'input' && !this.open) {
            this.openList();
            return;
        }
        const v = this.visible;
        if (!v.length) return;
        let i = v.findIndex((o) => o.value === this.activeValue);
        i = i < 0 ? 0 : (i + dir + v.length) % v.length;
        this.activeValue = v[i].value;
    },
    edge(pos) {
        const v = this.visible;
        if (!v.length) return;
        this.activeValue = (pos === 'last' ? v[v.length - 1] : v[0]).value;
    },
    openList() {
        this.open = true;
        if (this.trigger === 'button') {
            this.query = '';
            this.$nextTick(() => {
                this.ensureActive();
                (this.$refs.search || this.$refs.list)?.focus();
            });
        } else {
            this.filtering = false;
            this.$nextTick(() => this.ensureActive());
        }
    },
    onInput() {
        if (!this.multiple) this.value = '';
        this.open = true;
        this.filtering = true;
        this.$nextTick(() => this.ensureActive());
    },
    toggle() {
        this.open ? this.close(false) : this.openList();
    },
    close(returnFocus = true) {
        if (!this.open) return;
        this.open = false;
        this.filtering = false;
        if (this.trigger === 'button' && returnFocus) this.$nextTick(() => this.$refs.trigger?.focus());
    },
    selectActive() {
        if (this.activeValue != null) this.select(this.activeValue);
    },
    select(v) {
        if (this.multiple) {
            const i = this.value.indexOf(v);
            if (i === -1) this.value.push(v); else this.value.splice(i, 1);
            if (this.trigger === 'input') {
                this.query = '';
                this.filtering = false;
                this.$nextTick(() => this.$refs.input?.focus());
            }
            return;
        }
        if (this.trigger === 'input') {
            const o = this.options.find((x) => x.value === v);
            if (o) {
                this.value = o.value;
                this.query = o.label;
            }
            this.close();
            return;
        }
        this.value = this.value === v ? '' : v;
        this.close();
        this.query = '';
    },
    remove(v) {
        const i = this.value.indexOf(v);
        if (i !== -1) this.value.splice(i, 1);
    },
    backspace() {
        if (this.multiple && this.query === '' && this.value.length) this.value.splice(this.value.length - 1, 1);
    },
    registerOption(value, label) {
        if (!this.options.some((o) => o.value === value)) {
            this.options.push({value, label});
        }
    },
});

/**
 * Menu data.
 */
const uiMenuData = (config = {}) => ({
    open: config.open ?? false,
    x: 0,
    y: 0,
    _menu: null,
    _trigger: null,
    _closeTimer: null,
    // Context-menu entry point: open at the pointer position with first item focused.
    openAt(ev) {
        if (ev) {
            ev.preventDefault();
            this.x = ev.clientX;
            this.y = ev.clientY;
            this._trigger = ev.currentTarget || this._trigger;
        }
        this.openMenu();
    },
    get _items() {
        if (!this._menu) return [];
        return Array.from(this._menu.querySelectorAll('[role="menuitem"], [role="menuitemcheckbox"], [role="menuitemradio"]')).filter(
            (i) => i.getAttribute('aria-disabled') !== 'true' && !i.hasAttribute('disabled') && i.offsetParent !== null,
        );
    },
    openMenu(focus) {
        this.cancelClose();
        this.open = true;
        this.$nextTick(() => {
            if (!this._menu) return;
            const items = this._items;
            if (focus === 'first') (items[0] || this._menu).focus();
            else if (focus === 'last') (items[items.length - 1] || this._menu).focus();
            else this._menu.focus();
        });
    },
    toggleMenu() {
        this.open ? this.closeMenu(false) : this.openMenu();
    },
    closeMenu(returnFocus = true) {
        this.cancelClose();
        if (!this.open) return;
        this.open = false;
        if (returnFocus && this._trigger) this.$nextTick(() => this._trigger.focus());
    },

    closeSoon(delay = 120) {
        clearTimeout(this._closeTimer);
        this._closeTimer = setTimeout(() => this.closeMenu(false), delay);
    },
    cancelClose() {
        clearTimeout(this._closeTimer);
    },
});

/**
 * Menubar data — bar-level roving focus and auto-open coordination.
 *
 * Each menu item inside the bar should use x-data="uiMenu()" for its own
 * open/close state. The bar root uses this component to track which trigger
 * is active and to handle left/right arrow navigation between triggers.
 */
const uiMenubarData = () => ({
    _triggers: [],
    // Register a trigger element. Called by each menubar-trigger via x-init.
    registerTrigger(el) {
        if (!this._triggers.includes(el)) this._triggers.push(el);
    },
    // Returns true if any menu in this bar is currently open.
    get anyOpen() {
        return this._triggers.some((t) => t.getAttribute('data-state') === 'open');
    },
    // Move focus (and optionally open the menu) to the trigger at offset +1/-1.
    moveTrigger(el, dir) {
        const idx = this._triggers.indexOf(el);
        if (idx === -1) return;
        const next = this._triggers[(idx + dir + this._triggers.length) % this._triggers.length];
        if (next) {
            next.focus();
            if (this.anyOpen) next.click();
        }
    },
});

/**
 * UI select data.
 */
const uiSelectData = (config = {}) => ({
    multiple: !!config.multiple,
    open: false,
    value: config.entangled ? config.value : (config.multiple ? (Array.isArray(config.value) ? config.value.map(String) : config.value != null && config.value !== '' ? [String(config.value)] : []) : config.value != null ? String(config.value) : ''),
    label: '',
    selected: [],
    _list: null,
    _trigger: null,
    get _options() {
        return this._list
            ? Array.from(this._list.querySelectorAll('[role="option"]')).filter((o) => o.getAttribute('aria-disabled') !== 'true' && o.offsetParent !== null,)
            : [];
    },
    isSelected(val) {
        val = String(val);
        return this.multiple ? this.value.includes(val) : this.value === val;
    },
    openList() {
        this.open = true;
        this.$nextTick(() => {
            if (!this._list) return;
            const opts = this._options;
            (opts.find((o) => this.isSelected(o.dataset.value)) || opts[0] || this._list).focus();
        });
    },
    toggleList() {
        this.open ? this.close(false) : this.openList();
    },
    close(returnFocus = true) {
        if (!this.open) return;
        this.open = false;
        if (returnFocus && this._trigger) this.$nextTick(() => this._trigger.focus());
    },
    selectOption(val, lbl) {
        val = String(val);
        if (this.multiple) {
            const i = this.value.indexOf(val);
            if (i === -1) {
                this.value.push(val);
                if (!this.selected.some((s) => s.value === val)) this.selected.push({value: val, label: lbl});
            } else {
                this.value.splice(i, 1);
                const j = this.selected.findIndex((s) => s.value === val);
                if (j !== -1) this.selected.splice(j, 1);
            }
            return;
        }
        this.value = val;
        this.label = lbl;
        this.close();
    },
    seedSelected(val, lbl) {
        val = String(val);
        if (this.multiple) {
            if (this.isSelected(val) && !this.selected.some((s) => s.value === val)) {
                this.selected.push({value: val, label: lbl});
            }
        } else if (this.value === val) {
            this.label = lbl;
        }
    },
    remove(val) {
        val = String(val);
        const i = this.value.indexOf(val);
        if (i !== -1) this.value.splice(i, 1);
        const j = this.selected.findIndex((s) => s.value === val);
        if (j !== -1) this.selected.splice(j, 1);
    },
});

/**
 * Trigger directive.
 */
function uiTriggerDirective(el, {expression}, {evaluate, effect}) {
    const config = expression ? evaluate(expression) : {};
    const control = resolveControl(el);
    if (!control) {
        return;
    }

    if (config.focusable && !control.matches('button, a[href], input, select, textarea, [tabindex]')) {
        control.tabIndex = 0;
    }

    if (config.id && !control.id) {
        control.id = config.id;
    }

    if (config.haspopup) {
        control.setAttribute('aria-haspopup', config.haspopup === true ? 'true' : config.haspopup);
    }

    if (config.controls) {
        control.setAttribute('aria-controls', config.controls);
    }

    if (config.labelledby) {
        control.setAttribute('aria-labelledby', config.labelledby);
    }

    if (config.describedby) {
        control.setAttribute('aria-describedby', config.describedby);
    }

    if (config.state === null) {
        return;
    }

    const stateExpression = config.state || 'open';
    effect(() => {
        let open = false;
        try {
            open = !!evaluate(stateExpression);
        } catch (_) {
        }

        control.setAttribute('aria-expanded', open ? 'true' : 'false');
        control.setAttribute('data-state', open ? 'open' : 'closed');
    });
}

/**
 * Carousel Alpine data — wraps Embla Carousel.
 *
 * Requires `embla-carousel` to be installed in the consumer's project.
 */
const uiCarouselData = (config = {}) => ({
    orientation: config.orientation || 'horizontal',
    opts: config.opts || {},
    canScrollPrev: false,
    canScrollNext: false,
    _embla: null,
    init() {
        // Lazily import Embla so it is only required when the component is used.
        import('embla-carousel').then(({default: EmblaCarousel}) => {
            const viewport = this.$refs.viewport;
            if (!viewport) return;

            this._embla = EmblaCarousel(viewport, {
                ...this.opts,
                axis: this.orientation === 'horizontal' ? 'x' : 'y',
            });

            const update = () => {
                this.canScrollPrev = this._embla.canScrollPrev();
                this.canScrollNext = this._embla.canScrollNext();
            };

            this._embla.on('init', update);
            this._embla.on('reInit', update);
            this._embla.on('select', update);

            update();
        });
    },
    destroy() {
        this._embla?.destroy();
    },
    scrollPrev() {
        this._embla?.scrollPrev();
    },
    scrollNext() {
        this._embla?.scrollNext();
    },
});

/**
 * Command palette Alpine data.
 */
const uiCommandData = (config = {}) => ({
    query: '',
    activeId: null,
    _entries: [],
    registerItem(el, keyword, disabled) {
        const id = ensureId(el, 'ui-cmd-item');
        this._entries.push({ id, el, keyword: (keyword || '').toLowerCase(), disabled: !!disabled });
        return id;
    },
    matches(kw) {
        return (kw || '').toLowerCase().includes(this.query.toLowerCase());
    },
    get _visible() {
        return this._entries.filter((i) => !i.disabled && this.matches(i.keyword) && i.el.offsetParent !== null);
    },
    get visibleCount() {
        return this._entries.filter((i) => this.matches(i.keyword)).length;
    },
    ensureActive() {
        const vis = this._visible;
        if (!vis.length) {
            this.activeId = null;
        } else if (!vis.some((i) => i.id === this.activeId)) {
            this.activeId = vis[0].id;
        }
    },
    move(dir) {
        const vis = this._visible;
        if (!vis.length) return;
        let idx = vis.findIndex((i) => i.id === this.activeId);
        idx = idx < 0 ? (dir > 0 ? 0 : vis.length - 1) : (idx + dir + vis.length) % vis.length;
        this.activeId = vis[idx].id;
        vis[idx].el.scrollIntoView({ block: 'nearest' });
    },
    edge(pos) {
        const vis = this._visible;
        if (!vis.length) return;
        const it = pos === 'last' ? vis[vis.length - 1] : vis[0];
        this.activeId = it.id;
        it.el.scrollIntoView({ block: 'nearest' });
    },
    selectActive() {
        const it = this._entries.find((i) => i.id === this.activeId);
        if (it && !it.disabled) it.el.click();
    },
});

function _addMonths(d, n) {
    return new Date(d.getFullYear(), d.getMonth() + n, 1);
}

function _parse(s) {
    if (!s) return null;
    if (s instanceof Date) return new Date(s.getFullYear(), s.getMonth(), s.getDate());
    const p = String(s).split('-').map(Number);
    return new Date(p[0], (p[1] || 1) - 1, p[2] || 1);
}

function _sameDay(a, b) {
    return a && b && _ymd(a) === _ymd(b);
}

function _ymd(d) {
    return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
}

/**
 * Registers the UI.
 */
export function registerUI(Alpine, options = {}) {
    Alpine.plugin(anchor);
    Alpine.plugin(focus);
    Alpine.plugin(collapse);

    Alpine.data('uiCalendar', uiCalendarData);
    Alpine.data('uiCarousel', uiCarouselData);
    Alpine.data('uiCommand', uiCommandData);
    Alpine.data('uiListbox', uiListboxData);
    Alpine.data('uiMenu', uiMenuData);
    Alpine.data('uiContextMenu', uiMenuData);
    Alpine.data('uiMenubar', uiMenubarData);
    Alpine.data('uiSelect', uiSelectData);

    Alpine.directive('ui-anchor', uiAnchorDirective);
    Alpine.directive('ui-item-aligned', uiItemAlignedDirective);
    Alpine.directive('ui-dialog-layer', uiDialogLayerDirective);
    Alpine.directive('ui-labelledby', uiLabelledByDirective);
    Alpine.directive('ui-field', uiFieldDirective);
    Alpine.directive('ui-trigger', uiTriggerDirective);
}
