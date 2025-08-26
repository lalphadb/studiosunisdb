/**
 * StudiosDB v6 Pro - Application JavaScript Ultra-Professionnelle
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
const defaultName = 'StudiosDB v6 Pro';
const defaultVersion = '6.0.0';

// Rendre route() disponible globalement AVANT createInertiaApp
if (typeof window !== 'undefined') {
    // Utiliser Ziggy du serveur ou le fallback local
    const ziggyConfig = window.Ziggy || ZiggyFallback;
    
    // Créer la fonction route globale
    window.route = (name, params, absolute) => {
        const route = ziggyConfig.routes[name];
        if (!route) {
            console.error(`Route ${name} not found`);
            return '#';
        }
        
        let uri = route.uri;
        
        // Remplacer les paramètres
        if (params) {
            if (route.parameters) {
                route.parameters.forEach(param => {
                    if (params[param]) {
                        uri = uri.replace(`{${param}}`, params[param]);
                    }
                });
            }
        }
        
        // Retirer les paramètres optionnels
        uri = uri.replace(/\{[^}]*\?\}/g, '');
        
        // Construire l'URL complète
        const baseUrl = ziggyConfig.url || 'http://localhost:8000';
        return absolute ? `${baseUrl}/${uri}` : `/${uri}`;
    };
}

// Configuration Inertia App
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';
createInertiaApp({
    title: (title) => {
        // Title uses server-provided app name when available
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
        
        // NE PAS ajouter le layout automatiquement si la page l'a déjà défini
        // Les pages définissent leur propre layout
        
        return page;
    },
    
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Plugins Vue
        app.use(plugin);
        
        // Configurer Ziggy pour Vue
        const ziggyConfig = typeof window !== 'undefined' && window.Ziggy
            ? { ...window.Ziggy }
            : { ...ZiggyFallback };

        app.use(ZiggyVue, {
            location: new URL(window.location.href),
            ...ziggyConfig,
        });
        
        // Rendre route() disponible dans tous les composants
        app.config.globalProperties.$route = window.route;
        app.mixin({
            methods: {
                route: window.route
            }
        });
        
        // Variables globales (prefer server props, then window, then defaults)
        const shared = props.initialPage?.props?.app || {};
        const metaFromWindow = typeof window !== 'undefined' ? (window.APP_META || {}) : {};
        app.config.globalProperties.$appName = shared.name || metaFromWindow.name || defaultName;
        app.config.globalProperties.$appVersion = shared.version || metaFromWindow.version || defaultVersion;
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
export const appName = defaultName;
export const appVersion = defaultVersion;