import Alpine from 'alpinejs';
import './ui.js';
import {registerUI} from "./ui.js";

if (!window.Alpine) {
    registerUI(Alpine, { darkMode: 'light' });

    window.Alpine = Alpine;
    Alpine.start();
}
