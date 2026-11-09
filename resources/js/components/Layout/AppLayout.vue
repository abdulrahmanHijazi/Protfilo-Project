<script setup>
import { ref, watch, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Menu, X, Sun, Moon, Globe } from 'lucide-vue-next';
import BrandLogo from '@/components/ui/BrandLogo.vue';

const { t, locale } = useI18n();
const page = usePage();

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const mobileOpen = ref(false);
const isDark = ref(true);

const currentLocale = computed(() => page.props.locale || locale.value || 'en');
const isRtl = computed(() => (page.props.dir || (currentLocale.value === 'ar' ? 'rtl' : 'ltr')) === 'rtl');

function applyTheme() {
    document.documentElement.classList.toggle('dark', isDark.value);
}
function toggleTheme() {
    isDark.value = !isDark.value;
    try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light'); } catch (e) {}
    applyTheme();
}

function switchLocale() {
    const next = currentLocale.value === 'ar' ? 'en' : 'ar';
    try { localStorage.setItem('locale', next); } catch (e) {}
    locale.value = next;
    document.documentElement.lang = next;
    document.documentElement.dir = next === 'ar' ? 'rtl' : 'ltr';
    router.visit(`${page.url.split('?')[0]}?locale=${next}`, { preserveState: true, preserveScroll: true });
}

watch(() => page.props.locale, (next) => {
    if (next && ['en', 'ar'].includes(next)) {
        locale.value = next;
        document.documentElement.lang = next;
        document.documentElement.dir = page.props.dir || (next === 'ar' ? 'rtl' : 'ltr');
    }
});

onMounted(() => {
    // The blade head script already applied the saved choice or the admin's default mode.
    isDark.value = document.documentElement.classList.contains('dark');
    document.documentElement.lang = currentLocale.value;
    document.documentElement.dir = page.props.dir || (currentLocale.value === 'ar' ? 'rtl' : 'ltr');
    if (currentLocale.value) locale.value = currentLocale.value;
});

const links = computed(() => [
    { href: '/#about', label: t('nav.about') },
    { href: '/#experience', label: t('nav.experience') },
    { href: '/#projects', label: t('nav.projects') },
    { href: '/#skills', label: t('nav.skills') },
    { href: '/#services', label: t('nav.services') },
    { href: '/#education', label: t('nav.education') },
    { href: '/#contact', label: t('nav.contact') },
]);

const fullName = computed(() => {
    const s = props.settings || {};
    const loc = currentLocale.value;
    const first = s.first_name?.[loc] ?? s.first_name?.en ?? '';
    const last = s.last_name?.[loc] ?? s.last_name?.en ?? '';
    return `${first} ${last}`.trim() || 'Portfolio';
});

const socials = computed(() => props.settings?.socials ?? {});

const scrolled = ref(false);
const progress = ref(0);
function onScroll() {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    scrolled.value = window.scrollY > 12;
    progress.value = max > 0 ? Math.min(window.scrollY / max, 1) : 0;
}
onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
    <div :class="['min-h-screen bg-(--bg) text-(--text)', currentLocale === 'ar' ? 'font-arabic' : '']">
        <!-- Header -->
        <div class="fixed top-0 inset-x-0 z-[60] h-0.5 origin-left bg-gradient-to-r from-(--accent-deep) via-(--accent) to-(--accent-soft) rtl:origin-right" :style="{ transform: `scaleX(${progress})` }"></div>
        <header :class="['fixed top-0 inset-x-0 z-50 border-b transition-colors duration-300', scrolled ? 'border-(--line-soft) bg-(--bg)/80 backdrop-blur-xl' : 'border-transparent bg-transparent']">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
                <a href="/" class="flex items-center gap-2 font-extrabold text-lg">
                    <img v-if="settings?.logo_url" :src="settings.logo_url" :alt="fullName" class="h-9 w-9 object-contain" />
                    <BrandLogo v-else :size="38" class="transition-transform duration-500 hover:rotate-[360deg]" />
                    <span class="hidden sm:inline text-gold-gradient">{{ fullName }}</span>
                </a>

                <div class="hidden lg:flex items-center gap-6 text-sm text-(--muted)">
                    <a v-for="l in links" :key="l.href" :href="l.href" class="hover:text-(--accent-soft) transition-colors">{{ l.label }}</a>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="switchLocale" :title="t('common.switch_language')"
                        class="flex h-9 items-center gap-1.5 rounded-xl border border-(--line) bg-(--overlay) px-3 text-xs font-bold hover:border-(--accent)/50 hover:text-(--accent-soft)">
                        <Globe class="h-4 w-4" />
                        {{ currentLocale === 'ar' ? 'EN' : 'عربي' }}
                    </button>
                    <button @click="toggleTheme" :title="t('common.toggle_theme')"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-(--line) bg-(--overlay) hover:border-(--accent)/50 hover:text-(--accent-soft)">
                        <Sun v-if="isDark" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </button>
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-xl border border-(--line) bg-(--overlay)"
                        :aria-label="mobileOpen ? t('common.close_menu') : t('common.open_menu')">
                        <X v-if="mobileOpen" class="h-4 w-4" />
                        <Menu v-else class="h-4 w-4" />
                    </button>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="lg:hidden border-t border-(--line-soft) bg-(--bg)/95 px-4 py-4 backdrop-blur-xl">
                <div class="flex flex-col gap-1">
                    <a v-for="l in links" :key="l.href" :href="l.href" @click="mobileOpen = false"
                        class="rounded-lg px-3 py-2.5 text-sm text-(--text) hover:bg-(--overlay) hover:text-(--accent-soft)">{{ l.label }}</a>
                </div>
            </div>
        </header>

        <main class="pt-16">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-(--line-soft) bg-(--bg-soft) mt-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="flex items-center gap-3 text-sm text-(--muted)">
                    <BrandLogo :size="28" />
                    © {{ new Date().getFullYear() }} {{ fullName }} — {{ t('footer.rights') }}
                </p>
                <div class="flex items-center gap-3 text-xs text-(--faint)">
                    <span>{{ t('footer.made_with') }}</span>
                    <span v-if="socials.github || socials.gitlab || socials.linkedin">·</span>
                    <a v-if="socials.gitlab" :href="socials.gitlab" target="_blank" class="hover:text-(--accent-soft)">GitLab</a>
                    <a v-if="socials.github" :href="socials.github" target="_blank" class="hover:text-(--accent-soft)">GitHub</a>
                    <a v-if="socials.linkedin" :href="socials.linkedin" target="_blank" class="hover:text-(--accent-soft)">LinkedIn</a>
                    <a v-if="socials.twitter" :href="socials.twitter" target="_blank" class="hover:text-(--accent-soft)">X</a>
                </div>
                <a href="#" class="text-xs text-(--faint) hover:text-(--accent-soft)">{{ t('footer.back_to_top') }} ↑</a>
            </div>
        </footer>
    </div>
</template>
