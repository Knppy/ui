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
function ensureId(node, prefix = 'blat') {
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
 * Anchor directive.
 */
const ANCHOR_SIDES = ['top', 'right', 'bottom', 'left'];
const ANCHOR_ALIGNS = ['start', 'end'];

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
            const update = () => computePosition(reference, el, {
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
            stop = autoUpdate(reference, el, update);
        });
    });

    cleanup(() => stop && stop());
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
        Object.assign(el.style, {position: 'fixed', visibility: 'hidden', left: '0px', top: '0px', minWidth: `${triggerRect.width}px`});

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

    cleanup(() => {});
}

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
 * Registers the UI.
 */
export function registerUI(Alpine, options = {}) {
    Alpine.plugin(anchor);
    Alpine.plugin(focus);
    Alpine.plugin(collapse);

    Alpine.data('uiSelect', uiSelectData);

    Alpine.directive('ui-anchor', uiAnchorDirective);
    Alpine.directive('ui-item-aligned', uiItemAlignedDirective);
    Alpine.directive('ui-dialog-layer', uiDialogLayerDirective);
    Alpine.directive('ui-labelledby', uiLabelledByDirective);
    Alpine.directive('ui-trigger', uiTriggerDirective);
}
