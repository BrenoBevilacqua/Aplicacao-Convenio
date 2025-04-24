import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/css/app.css', // Adiciona o CSS do Tailwind
        ]),
    ],
    server: {
        host: true,  // Permite conexões externas
        allowedHosts: 'all',
    },
    base: '/',
});