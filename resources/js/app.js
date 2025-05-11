import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { createHead } from '@vueuse/head'
import VirtualList from 'vue3-virtual-scroll-list'
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import 'leaflet/dist/leaflet.css'


const head = createHead()
const appName = import.meta.env.VITE_APP_NAME || 'Uptown';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(head)
            .component('virtual-list', VirtualList)
            .use(Vue3Toastify, {
                autoClose: 3000,
                position: "top-right",
                theme: "light",
            })
            .mount(el);


    },
    progress: {
        color: '#d23f95',
    },

});

