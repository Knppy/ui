import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import {bunny} from "laravel-vite-plugin/fonts";
import {defineConfig} from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'vendor/orchestra/testbench-core/laravel/resources/css/app.css',
            ],
            publicDirectory: 'vendor/orchestra/testbench-core/laravel/public',
            buildDirectory: 'build',
            hotFile: 'vendor/orchestra/testbench-core/laravel/public/hot',
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: [
                '**/vendor/**',
                '**/node_modules/**',
                '**/storage/**',
            ],
        },
    },
    build: {
        outDir: 'vendor/orchestra/testbench-core/laravel/public/build',
    },
});
