import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';

const appName = import.meta.env.VITE_APP_NAME || 'StudiosDB';

// Déclaration globale Ziggy (injecté par Laravel via @routes)
declare global {
    var Ziggy: any;
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore - Fix pour types Inertia.js conflictuels
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // @ts-ignore - Fix pour types Vue 3 + Inertia.js
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4F46E5',
    },
});
