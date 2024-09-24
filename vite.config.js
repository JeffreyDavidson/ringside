import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/plugins.bundle.css',
                'resources/css/style.bundle.css',
            ],
            refresh: true,
            postcss: [
                tailwindcss(),
                autoprefixer(),
            ]
        }),
    ]
});
