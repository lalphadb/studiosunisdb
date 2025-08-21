/**
 * StudiosDB v5 Pro - Application JavaScript Ultra-Professionnelle
 * Framework: Vue 3 + Composition API + Inertia.js
 * Version: Laravel 11.x Compatible
 * Optimisé avec Code Splitting et Lazy Loading
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

// Import statique du layout par défaut (utilisé fréquemment)
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';

// Cache pour les composants déjà chargés
const pageCache = new Map();

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    
    resolve: async (name) => {
        // Vérifier le cache d'abord
        if (pageCache.has(name)) {
            return pageCache.get(name);
        }

        // Lazy loading des pages (sans eager: true pour le code splitting)
        const pages = import.meta.glob('./Pages/**/*.vue');
        const pagePath = `./Pages/${name}.vue`;
        
        if (!pages[pagePath]) {
            console.error(`Page component not found: ${name}`);
            throw new Error(`Page component not found: ${name}`);
        }
        
        // Charger la page de manière asynchrone
        const page = await pages[pagePath]();
        
        // Ajouter layout par défaut si non spécifié
        if (!page.default.layout && !name.startsWith('Auth/')) {
            page.default.layout = AuthenticatedLayout;
        }
        
        // Mettre en cache pour les utilisations futures
        pageCache.set(name, page);
        
        return page;
    },
    
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Plugins Vue
        app.use(plugin);
        
        // Configuration Ziggy
        const ziggyConfig = typeof window !== 'undefined' && window.Ziggy
            ? { ...window.Ziggy }
            : { ...ZiggyFallback };

        app.use(ZiggyVue, {
            location: new URL(window.location.href),
            ...ziggyConfig,
        });
        
        // Variables globales (optimisées)
        const globalProps = app.config.globalProperties;
        globalProps.$appName = appName;
        globalProps.$appVersion = appVersion;
        
        // User data (lazy)
        Object.defineProperty(globalProps, '$user', {
            get() {
                return props.initialPage.props.auth?.user || null;
            },
            configurable: true
        });
        
        // Configuration de production
        if (import.meta.env.PROD) {
            app.config.performance = false;
            app.config.devtools = false;
            
            // Désactiver les warnings en production
            app.config.warnHandler = () => null;
        }
        
        // Gestion des erreurs globales (simplifiée en production)
        app.config.errorHandler = import.meta.env.DEV 
            ? (err, instance, info) => {
                console.error('Vue Error:', err, info);
                console.error('Component:', instance);
            }
            : (err) => {
                // En production, logger silencieusement
                if (window.console && console.error) {
                    console.error('Application error:', err.message);
                }
            };
        
        // Filtres globaux (lazy loading)
        globalProps.$filters = {
            currency: (value) => {
                if (!value && value !== 0) return '';
                return new Intl.NumberFormat('fr-CA', {
                    style: 'currency',
                    currency: 'CAD',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(value);
            },
            
            date: (value, format = 'short') => {
                if (!value) return '';
                const date = new Date(value);
                if (isNaN(date.getTime())) return value;
                
                const options = {
                    short: { day: '2-digit', month: '2-digit', year: 'numeric' },
                    long: { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' },
                    time: { hour: '2-digit', minute: '2-digit' },
                    datetime: { 
                        day: '2-digit', 
                        month: '2-digit', 
                        year: 'numeric',
                        hour: '2-digit', 
                        minute: '2-digit' 
                    },
                };
                
                return new Intl.DateTimeFormat('fr-CA', options[format] || options.short)
                    .format(date);
            },
            
            truncate: (text, length = 50) => {
                if (!text) return '';
                return text.length > length 
                    ? text.substring(0, length).trim() + '...' 
                    : text;
            },
            
            capitalize: (text) => {
                if (!text) return '';
                return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
            },
            
            phone: (value) => {
                if (!value) return '';
                const cleaned = value.replace(/\D/g, '');
                const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
                if (match) {
                    return `(${match[1]}) ${match[2]}-${match[3]}`;
                }
                return value;
            }
        };
        
        // Préchargement intelligent des modules critiques
        if (import.meta.env.PROD) {
            // Précharger les composants UI fréquemment utilisés
            setTimeout(() => {
                // Précharger les pages courantes en arrière-plan
                const commonPages = [
                    './Pages/Dashboard.vue',
                    './Pages/Membres/Index.vue',
                    './Pages/Cours/Index.vue'
                ];
                
                const pages = import.meta.glob('./Pages/**/*.vue');
                commonPages.forEach(path => {
                    if (pages[path]) {
                        pages[path]().catch(() => {}); // Ignorer les erreurs de préchargement
                    }
                });
            }, 2000); // Attendre 2 secondes après le chargement initial
        }
        
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

// Nettoyer le cache si la mémoire devient un problème
if (import.meta.env.PROD) {
    window.addEventListener('popstate', () => {
        // Garder seulement les 10 dernières pages en cache
        if (pageCache.size > 10) {
            const entriesToKeep = Array.from(pageCache.entries()).slice(-10);
            pageCache.clear();
            entriesToKeep.forEach(([key, value]) => pageCache.set(key, value));
        }
    });
}
