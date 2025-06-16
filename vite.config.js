// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // This is your new primary CSS/Sass entry point
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        // Remove tailwindcss() plugin here
    ],
});


