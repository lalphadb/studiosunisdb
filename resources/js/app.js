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
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Routing et helpers
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';

// Configuration globale de l'application
const appName = import.meta.env.VITE_APP_NAME || 'StudiosDB v5 Pro';
const appVersion = '5.0.0';

// Configuration Inertia App
createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue', { eager: false })
        );
        
        // Ajouter layout par défaut si non spécifié
        return page.then((module) => {
            if (!module.default.layout && !name.startsWith('Auth/')) {
                module.default.layout = import('./Layouts/AuthenticatedLayout.vue');
            }
            return module;
        });
    },
    
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Plugins Vue
        app.use(plugin);
        app.use(ZiggyVue);
        
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
