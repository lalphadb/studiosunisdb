// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

export default defineConfig(({ mode }) => {
  const isProd = mode === 'production';

  return {
    plugins: [
      // Entrées front principales (ajuste si besoin)
      laravel({
        input: [
          'resources/js/app.js',
          'resources/css/app.css',
        ],
        refresh: true,
      }),

      // Vue 3 (Inertia)
      vue({
        // (garde false si tu n'utilises pas reactivity transform)
        reactivityTransform: false,
        template: {
          // ⚠️ Correctif important : base doit être null (et non "nul")
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
        '~': path.resolve(__dirname, 'resources'),
      },
    },

    server: {
      host: true,
      port: 5173,
      strictPort: false,
      // Si tu développes sur un domaine local (ex. studios.local),
      // remplace 'localhost' par ce domaine :
      // hmr: { host: 'studios.local' },
      hmr: { host: 'localhost' },
    },

    css: {
      // Utile en dev pour debugger Tailwind/PostCSS
      devSourcemap: true,
    },

    build: {
      sourcemap: !isProd,
      minify: isProd ? 'terser' : false,
      chunkSizeWarningLimit: 900,
      rollupOptions: {
        output: {
          // Découpage simple et efficace (ajuste selon tes libs)
          manualChunks: {
            vue: ['vue', '@inertiajs/vue3'],
            vendor: ['axios', 'lodash', 'chart.js', 'vue-chartjs'],
          },
        },
      },
    },

    define: {
      // Allège le build et évite l’API options si tu es full <script setup>
      __VUE_OPTIONS_API__: false,
      __VUE_PROD_DEVTOOLS__: false,
    },
  };
});

