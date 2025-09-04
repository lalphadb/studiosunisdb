/**
 * StudiosDB v6 Pro - Application JavaScript
 * Framework: Vue 3 + Composition API + Inertia.js
 * Version: Laravel 12.x Compatible
 */

// Importation des styles CSS
import '../css/app.css';

// Core Vue 3 et Inertia.js
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

// Route helper global pour StudiosDB
window.route = (name, params = {}) => {
    const baseUrl = window.location.origin;
    const routes = {
        'cours.index': '/cours',
        'cours.create': '/cours/create',
        'cours.show': (id) => `/cours/${id}`,
        'cours.edit': (id) => `/cours/${id}/edit`,
        'cours.destroy': (id) => `/cours/${id}`,
        'cours.duplicate': (id) => `/cours/${id}/duplicate`,
        'cours.export': '/cours/export',
        'cours.planning': '/cours/planning',
        'membres.index': '/membres',
        'membres.create': '/membres/create',
        'membres.show': (id) => `/membres/${id}`,
        'membres.edit': (id) => `/membres/${id}/edit`,
        'dashboard': '/dashboard',
        'utilisateurs.index': '/utilisateurs',
        'presences.index': '/presences'
    };
    
    const route = routes[name];
    if (typeof route === 'function') {
        return baseUrl + route(params);
    }
    return baseUrl + (route || '/');
};

// Configuration globale de l'application
const defaultName = 'StudiosDB v6 Pro';
const defaultVersion = '6.0.0';

// Configuration Inertia App
createInertiaApp({
    title: (title) => {
        const provided = (typeof window !== 'undefined' && window.APP_META?.name) || defaultName;
        return title ? `${title} - ${provided}` : provided;
    },
    
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        
        if (!page) {
            console.error(`Page component not found: ${name}`);
            throw new Error(`Page component not found: ${name}`);
        }
        
        return page;
    },
    
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Plugins Vue
        app.use(plugin);
        
        // Variables globales
        const shared = props.initialPage?.props?.app || {};
        const metaFromWindow = typeof window !== 'undefined' ? (window.APP_META || {}) : {};
        app.config.globalProperties.$appName = shared.name || metaFromWindow.name || defaultName;
        app.config.globalProperties.$appVersion = shared.version || metaFromWindow.version || defaultVersion;
        app.config.globalProperties.$user = props.initialPage.props.auth?.user || null;
        
        // Helper route global
        app.config.globalProperties.$route = window.route;
        window.route = window.route; // Assurer disponibilité globale
        
        // Configuration de production
        if (import.meta.env.PROD) {
            app.config.performance = false;
            app.config.devtools = false;
        }
        
        // Gestion des erreurs globales
        app.config.errorHandler = (err, instance, info) => {
            console.error('Vue Error:', err, info);
        };
        
        // Filtres globaux utiles
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
export const appName = defaultName;
export const appVersion = defaultVersion;
