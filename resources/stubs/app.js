import Alpine from 'alpinejs';
import {registerUI} from './ui-core';

if (!window.Alpine) {
    window.Alpine = Alpine;
    registerUI(Alpine);
    Alpine.start();
}
