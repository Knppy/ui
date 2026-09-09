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

    // Directives.

    // Magic.

    console.info('UI loaded!')
}
