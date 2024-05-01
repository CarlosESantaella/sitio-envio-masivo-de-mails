import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // base: 'https://massive-emails.devsprinters.com',
            input: [
                './resources/css/app.css', 
                './resources/js/app.js'
            ],
            refresh: true,
        }),
    ]
});
