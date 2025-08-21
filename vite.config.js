import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
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
        host: '127.0.0.1',
        port: 5173,
        cors: true,
        hmr: {
            host: '127.0.0.1',
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },

    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        // Augmenter la limite d'avertissement temporairement
        chunkSizeWarningLimit: 600,
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
            },
            output: {
                // Configuration du code splitting manuel
                manualChunks: (id) => {
                    // Vendor chunks - séparer les grosses librairies
                    if (id.includes('node_modules')) {
                        // Framework core (Vue, Inertia)
                        if (id.includes('vue') || id.includes('@vue')) {
                            return 'vue-core';
                        }
                        if (id.includes('@inertiajs')) {
                            return 'inertia';
                        }
                        // UI Libraries
                        if (id.includes('@headlessui') || id.includes('@heroicons')) {
                            return 'ui-libs';
                        }
                        // Utilities
                        if (id.includes('axios') || id.includes('lodash')) {
                            return 'utils';
                        }
                        // Ziggy routing
                        if (id.includes('ziggy')) {
                            return 'ziggy';
                        }
                        // Tout autre vendor
                        return 'vendor';
                    }
                    
                    // Application chunks - séparer par module
                    if (id.includes('resources/js')) {
                        // Layouts dans un chunk séparé
                        if (id.includes('/Layouts/')) {
                            return 'layouts';
                        }
                        // Components réutilisables
                        if (id.includes('/Components/')) {
                            return 'components';
                        }
                        // Pages par module
                        if (id.includes('/Pages/')) {
                            // Dashboard module
                            if (id.includes('/Dashboard')) {
                                return 'pages-dashboard';
                            }
                            // Auth module
                            if (id.includes('/Auth/')) {
                                return 'pages-auth';
                            }
                            // Membres module
                            if (id.includes('/Membres/')) {
                                return 'pages-membres';
                            }
                            // Cours module
                            if (id.includes('/Cours/')) {
                                return 'pages-cours';
                            }
                            // Présences module
                            if (id.includes('/Presences/')) {
                                return 'pages-presences';
                            }
                            // Paiements module
                            if (id.includes('/Paiements/')) {
                                return 'pages-paiements';
                            }
                            // Autres pages
                            return 'pages-misc';
                        }
                    }
                },
                // Optimisation des chunks
                chunkFileNames: (chunkInfo) => {
                    const facadeModuleId = chunkInfo.facadeModuleId ? chunkInfo.facadeModuleId.split('/').pop() : 'chunk';
                    return `assets/js/${chunkInfo.name}-[hash].js`;
                },
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split('.').at(1);
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    }
                    return `assets/${extType}/[name]-[hash][extname]`;
                },
            },
        },
        // Optimisations supplémentaires
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Supprimer console.log en production
                drop_debugger: true,
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
        exclude: [
            '@inertiajs/server'
        ]
    },

    define: {
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
    },
});
