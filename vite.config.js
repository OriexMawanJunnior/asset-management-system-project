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
    build: {
        outDir: 'public/build',
        rollupOptions: {
            output: {
              entryFileNames: 'assets/app-[hash].js',
              chunkFileNames: 'assets/app-[hash].js',
              assetFileNames: 'assets/app-[hash].[ext]'
            }
        }
    },
});
