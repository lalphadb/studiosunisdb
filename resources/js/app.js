import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Head } from '@inertiajs/vue3';

const appName = 'StudiosDB v5 Pro';

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
            .mount(el);
    },
    progress: {
        color: '#4F46E5',
    },
});
