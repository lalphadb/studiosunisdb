import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';

const appName = 'StudiosDB v5 Pro';

// Helper route simple (remplace Ziggy temporairement)
window.route = function(name, params = {}) {
    const routes = {
        'dashboard': '/dashboard',
        'membres.index': '/membres',
        'membres.create': '/membres/create',
        'membres.show': (id) => `/membres/${id}`,
        'membres.edit': (id) => `/membres/${id}/edit`,
        'cours.index': '/cours',
        'presences.tablette': '/presences/tablette',
        'presences.sauvegarder': '/presences/sauvegarder'
    };
    
    let route = routes[name];
    if (typeof route === 'function') {
        route = route(params);
    }
    return route || '/';
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el);
    },
    progress: {
        color: '#4F46E5',
    },
});
