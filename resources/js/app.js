/**
 * StudiosDB v5 Pro - Application JavaScript Ultra-Professionnelle
 * Framework: Vue 3 + Composition API + Inertia.js
 * Version: Laravel 11.x Compatible
 */

// Importation des styles CSS
import '../css/app.css';

// Core Vue 3 et Inertia.js
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

// Routing et helpers
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';
// Fallback local Ziggy routes (generated) if @routes is not injected
import { Ziggy as ZiggyFallback } from './ziggy.js';

// Configuration globale de l'application
const appName = import.meta.env.VITE_APP_NAME || 'StudiosDB v5 Pro';
const appVersion = '5.5.0';

// Configuration Inertia App
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';
createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        
        if (!page) {
            console.error(`Page component not found: ${name}`);
            throw new Error(`Page component not found: ${name}`);
        }
        
        // Ajouter layout par défaut si non spécifié
        if (!page.default.layout && !name.startsWith('Auth/')) {
            // Utilisation d'un import statique pour éviter l'avertissement Vite (mix dynamic/static import)
            page.default.layout = AuthenticatedLayout;
        }
        
        return page;
    },
    
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Plugins Vue
        app.use(plugin);
        // Prefer Blade-injected @routes; fallback to local ziggy.js when absent
        const ziggyConfig = typeof window !== 'undefined' && window.Ziggy
            ? { ...window.Ziggy }
            : { ...ZiggyFallback };

        app.use(ZiggyVue, {
            location: new URL(window.location.href),
            ...ziggyConfig,
        });
        
        // Variables globales
        app.config.globalProperties.$appName = appName;
        app.config.globalProperties.$appVersion = appVersion;
        app.config.globalProperties.$user = props.initialPage.props.auth?.user || null;
        
        // Configuration de production
        if (import.meta.env.PROD) {
            app.config.performance = false;
            app.config.devtools = false;
        }
        
        // Gestion des erreurs globales
        app.config.errorHandler = (err, instance, info) => {
            console.error('Vue Error:', err, info);
        };
        
        // Propriétés réactives globales
        app.config.globalProperties.$filters = {
            currency: (value) => {
                return new Intl.NumberFormat('fr-CA', {
                    style: 'currency',
                    currency: 'CAD',
                }).format(value);
            },
            
            date: (value, format = 'short') => {
                const options = {
                    short: { day: '2-digit', month: '2-digit', year: 'numeric' },
                    long: { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' },
                    time: { hour: '2-digit', minute: '2-digit' },
                };
                
                return new Intl.DateTimeFormat('fr-CA', options[format] || options.short)
                    .format(new Date(value));
            },
            
            truncate: (text, length = 50) => {
                return text && text.length > length 
                    ? text.substring(0, length) + '...' 
                    : text;
            },
        };
        
        // Monter l'application
        return app.mount(el);
    },
    
    progress: {
        delay: 250,
        color: '#4F46E5',
        includeCSS: true,
        showSpinner: true,
    },
});

// Export pour tests
export { appName, appVersion };
