import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss()
    ],
    server: {
        strictPort: true,
        https: true, // Menggunakan HTTPS untuk development server
        hmr: {
            host: 'localhost', // Ganti dengan domain development Anda jika perlu
        },
    },
    build: {
        outDir: 'public/build',
    },
});
