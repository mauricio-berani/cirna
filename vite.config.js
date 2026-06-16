import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: ['resources/**', 'routes/**', 'app/**/*.php'],
        }),
        tailwindcss(),
    ],
    css: { postcss: false },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
        },
        watch: {
            ignored: ['**/.claude/**', '**/storage/**', '**/vendor/**'],
        },
    },
});
