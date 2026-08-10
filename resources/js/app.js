import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import ToastContainer from './Components/ui/ToastContainer.vue';
import { toast } from './lib/toast';

const appName = import.meta.env.VITE_APP_NAME || 'MediCare';

createInertiaApp({
    title: (title) => `${title} — ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        app.component('ToastContainer', ToastContainer);

        app.mixin({
            mounted() {
                const flash = this.$page.props.flash;
                if (flash?.success) toast(flash.success, 'success', 'Success');
                if (flash?.error) toast(flash.error, 'error', 'Something went wrong');
                if (flash?.info) toast(flash.info, 'info');
            },
        });

        return app.mount(el);
    },
    progress: {
        color: '#2c9b98',
        includeCSS: true,
    },
});
