import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import { createAppI18n, resolveLocale } from './i18n';
import { reveal, tilt } from './lib/directives';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        if (!page) throw new Error(`Page not found: ./Pages/${name}.vue`);
        return page;
    },
    setup({ el, App, props, plugin }) {
        // inertia-laravel v2+ renders the page JSON in a separate
        // <script type="application/json"> tag; the plugin already parsed it
        // into props.initialPage. Parsing el.dataset.page crashes (undefined).
        const initialPage = props.initialPage || {};
        const locale = resolveLocale(initialPage);

        if (typeof localStorage !== 'undefined') localStorage.setItem('locale', locale);
        document.documentElement.lang = locale;
        document.documentElement.dir = initialPage.props.dir || (locale === 'ar' ? 'rtl' : 'ltr');

        const i18n = createAppI18n(locale);

        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(i18n);
        app.directive('reveal', reveal);
        app.directive('tilt', tilt);

        // Keep i18n locale in sync on Inertia navigation
        app.mixin({
            mounted() {
                if (this.$page?.props?.locale) {
                    const next = this.$page.props.locale;
                    if (['en', 'ar'].includes(next) && i18n.global.locale.value !== next) {
                        i18n.global.locale.value = next;
                    }
                }
            },
        });

        app.mount(el);
        return app;
    },
    title: (title) => (title ? `${title} — Abdulrahman Hijazi` : 'Abdulrahman Hijazi'),
    progress: {
        color: '#C9A227',
    },
});
