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
function uiAnchorDirective(el, {modifiers, expression}, {evaluateLater, cleanup}) {
    const placement = ANCHOR_PLACEMENTS.find((p) => modifiers.includes(p)) || 'bottom';
    let offsetValue = 0;
    if (modifiers.includes('offset')) {
        const i = modifiers.indexOf('offset');
        offsetValue = modifiers[i + 1] !== undefined ? Number(modifiers[i + 1]) : 0;
    }

    const allowFlip = !modifiers.includes('no-flip');
    const PAD = 8;
    const designMax = parseFloat(getComputedStyle(el).maxHeight) || Infinity;
    const getReference = evaluateLater(expression);

    let stop = null;
    getReference((reference) => {
        if (!reference || stop) return;
        const update = () => computePosition(reference, el, {
            strategy: 'fixed',
            placement,
            middleware: [flOffset(offsetValue), allowFlip && flip({padding: PAD}), shift({padding: PAD}), size({
                padding: PAD, apply({availableHeight, rects}) {
                    // Fit the available space but never exceed the design cap (min 140px so it
                    // never collapses). The popover carries overflow-y-auto, so it scrolls.
                    const h = Math.min(designMax, Math.max(140, Math.floor(availableHeight)));
                    el.style.maxHeight = Number.isFinite(h) ? `${h}px` : '';
                    // Match the trigger width as a minimum.
                    el.style.minWidth = `${rects.reference.width}px`;
                },
            }),].filter(Boolean),
        }).then(({x, y, placement: resolvedPlacement}) => {
            Object.assign(el.style, {position: 'fixed', left: `${x}px`, top: `${y}px`});
            // Expose resolved side and align as data attributes for CSS consumers.
            const [side, align = 'center'] = resolvedPlacement.split('-');
            el.dataset.side = side;
            el.dataset.align = align;
        });
        stop = autoUpdate(reference, el, update);
    });

    cleanup(() => stop && stop());
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
    Alpine.directive('ui-dialog-layer', uiDialogLayerDirective);
    Alpine.directive('ui-labelledby', uiLabelledByDirective);
    Alpine.directive('ui-trigger', uiTriggerDirective);
}
