import Alpine from 'alpinejs';
import {registerUI} from "./ui.js";

// Register Knppy UI's plugins, stores, and directives on whichever Alpine
// instance ends up running. Alpine dispatches this event on `document` at
// the very start of `Alpine.start()`, so this fires whether Alpine is
// started by this script or by Livewire's own bundled Alpine instance.
document.addEventListener('alpine:init', () => {
    registerUI(window.Alpine, { darkMode: 'light' });
}, { once: true });

// Livewire bundles and starts its own Alpine instance. If Livewire is on
// the page, let it own Alpine and skip starting our own copy to avoid
// running two conflicting instances. Livewire sets `window.Livewire`
// synchronously when its script executes, before any DOMContentLoaded
// listener runs, so this check is reliable regardless of script order.
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Livewire) {
        window.Alpine = Alpine;
        Alpine.start();
    }
});
