import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        emptyOutDir: true,
        lib: {
            entry: 'resources/js/app.js',
            formats: ['iife'],
            name: 'KnppyUi',
            fileName: () => 'ui.js',
        },
        outDir: 'dist',
    },
});
