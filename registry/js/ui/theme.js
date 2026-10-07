const themeStore = {
    // 'class' and 'system' enable dark mode, false disables it. 'light' starts in light mode.
    darkMode: 'light',
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

export default function (Alpine) {
    Alpine.store('theme', themeStore);
}
