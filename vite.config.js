import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
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
            '@': resolve(__dirname, 'resources/js'),
            '~': resolve(__dirname, 'resources'),
        },
    },

    server: {
        host: '0.0.0.0',
        port: 5173,
        cors: true,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },

    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        // Solution simple : augmenter la limite d'avertissement à 750KB
        chunkSizeWarningLimit: 750,
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
            },
        },
        // Minification basique pour réduire un peu la taille
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Supprimer les console.log en production
            },
        },
    },

    optimizeDeps: {
        include: [
            'vue',
            '@inertiajs/vue3',
            'axios',
            '@headlessui/vue',
            '@heroicons/vue/24/outline',
            '@heroicons/vue/24/solid',
        ],
    },

    define: {
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
    },
});
