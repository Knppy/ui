import Alpine from 'alpinejs';

// Every module in ./ui registers its own Alpine data, stores and magics.
// Adding a component with `php artisan ui:add` drops its module in here,
// so it is picked up automatically.
const modules = import.meta.glob('./ui/*.js', { eager: true });

// Alpine dispatches this event on `document` at the start of `Alpine.start()`,
// so this works whether we start Alpine or Livewire's bundled instance does.
document.addEventListener('alpine:init', () => {
    // Alpine ignores directives outside an x-data scope, so make sure the
    // whole page is one (e.g. for `x-on:click="$toast(...)"` on plain buttons).
    if (!document.body.hasAttribute('x-data')) {
        document.body.setAttribute('x-data', '');
    }

    Object.values(modules).forEach((module) => {
        if (typeof module.default === 'function') {
            module.default(window.Alpine);
        }
    });
}, { once: true });

// Livewire bundles and starts its own Alpine. When it is on the page, let it
// own Alpine to avoid running two conflicting instances.
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Livewire) {
        window.Alpine = Alpine;
        Alpine.start();
    }
});
