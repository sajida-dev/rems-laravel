import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    server: {
        host: 'localhost', // or use '127.0.0.1'
        port: 5173,
        hmr: {
            host: 'localhost', // or '127.0.0.1'
            protocol: 'ws',
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@Components': path.resolve(__dirname, 'resources/js/Components'),
            '@Pages': path.resolve(__dirname, 'resources/js/Pages'),
            '@Layouts': path.resolve(__dirname, 'resources/js/Layouts'),
        },
    },
});
