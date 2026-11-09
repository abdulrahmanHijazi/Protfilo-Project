import { createI18n } from 'vue-i18n';
import en from './lang/en.json';
import ar from './lang/ar.json';

export function resolveLocale(page) {
    const props = page?.props ?? {};
    const fromProps = props.locale;
    const fromStorage = typeof localStorage !== 'undefined' ? localStorage.getItem('locale') : null;
    const locale = fromProps || fromStorage || document.documentElement.lang || 'en';
    return ['en', 'ar'].includes(locale) ? locale : 'en';
}

export function createAppI18n(locale) {
    return createI18n({
        legacy: false,
        globalInjection: true,
        locale,
        fallbackLocale: 'en',
        messages: { en, ar },
    });
}
