<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { Download, ArrowRight, MapPin, Linkedin, Gitlab, Mail } from 'lucide-vue-next';
import ThreeStage from '@/components/three/ThreeStage.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    experiences: { type: Array, default: () => [] },
});

const tr = (v) => (v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '');
const firstName = computed(() => tr(props.settings?.first_name));
const lastName = computed(() => tr(props.settings?.last_name));
const jobTitle = computed(() => tr(props.settings?.job_title));
const summary = computed(() => tr(props.settings?.professional_summary));
const locationText = computed(() => tr(props.settings?.location));
const available = computed(() => !!props.settings?.available_for_work);
const avatar = computed(() => props.settings?.avatar_url || null);
const current = computed(() => props.experiences.find((e) => e.current));

const stack = ['PHP', 'Laravel', 'MySQL', 'Filament', 'Nova', 'REST APIs', 'Redis', 'Horizon', 'Reverb', 'WebSockets', 'Vue 3', 'Inertia.js', 'Stripe', 'FCM', 'Pest'];
</script>

<template>
    <section id="home" class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="bg-grid absolute inset-0"></div>
            <div class="absolute -top-40 start-1/4 h-[28rem] w-[28rem] rounded-full bg-(--accent)/15 blur-[140px]"></div>
            <div class="absolute bottom-0 end-0 h-80 w-80 rounded-full bg-(--accent)/10 blur-[120px]"></div>
        </div>

        <div class="relative mx-auto grid min-h-[calc(100svh-4rem)] max-w-7xl items-center gap-6 px-4 sm:px-6 pt-10 pb-16 lg:grid-cols-[1.05fr_1fr]">
            <div class="animate-fade-up order-2 lg:order-1">
                <div class="mb-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-(--accent)/30 bg-(--accent)/10 px-3 py-1 text-xs font-medium text-(--accent-soft)">
                        <span class="relative flex h-2 w-2">
                            <span v-if="available" class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span :class="['relative inline-flex h-2 w-2 rounded-full', available ? 'bg-emerald-400' : 'bg-red-400']"></span>
                        </span>
                        {{ available ? t('hero.available_for_work') : t('hero.not_available') }}
                    </span>
                    <span v-if="current" class="inline-flex items-center gap-1.5 rounded-full border border-(--line) bg-(--overlay) px-3 py-1 text-xs text-(--muted)">
                        {{ t('hero.currently') }} <b class="text-(--text)">{{ current.company }}</b>
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <img v-if="avatar" :src="avatar" :alt="`${firstName} ${lastName}`"
                        class="h-16 w-16 rounded-full border-2 border-(--accent)/50 object-cover gold-ring" />
                    <p class="text-sm font-medium text-(--muted)">{{ t('hero.greeting') }}</p>
                </div>
                <h1 class="mt-3 text-4xl sm:text-5xl md:text-6xl xl:text-7xl font-black leading-[1.05] tracking-tight">
                    <span class="text-(--text)">{{ firstName }} {{ lastName }}</span>
                </h1>
                <p class="mt-3 text-xl md:text-2xl font-bold text-gold-gradient">{{ jobTitle }}</p>
                <p class="mt-6 max-w-xl leading-relaxed text-(--muted)">{{ summary }}</p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#projects" class="group inline-flex h-12 items-center gap-2 rounded-xl bg-gradient-to-l from-(--accent) to-(--accent-soft) px-6 text-sm font-bold text-(--on-accent) shadow-lg shadow-(--accent)/25 hover:brightness-110">
                        {{ t('hero.cta_projects') }} <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1" />
                    </a>
                    <a v-if="settings?.cv_url" :href="settings.cv_url" target="_blank" class="inline-flex h-12 items-center gap-2 rounded-xl border border-(--accent)/40 px-6 text-sm font-bold text-(--accent-soft) hover:bg-(--accent)/10">
                        <Download class="h-4 w-4" /> {{ t('hero.download_cv') }}
                    </a>
                    <div class="flex items-center gap-2">
                        <a v-if="settings?.linkedin" :href="settings.linkedin" target="_blank" aria-label="LinkedIn" class="flex h-12 w-12 items-center justify-center rounded-xl border border-(--line) bg-(--overlay) text-(--muted) hover:border-(--accent)/50 hover:text-(--accent-soft)"><Linkedin class="h-5 w-5" /></a>
                        <a v-if="settings?.gitlab" :href="settings.gitlab" target="_blank" aria-label="GitLab" class="flex h-12 w-12 items-center justify-center rounded-xl border border-(--line) bg-(--overlay) text-(--muted) hover:border-(--accent)/50 hover:text-(--accent-soft)"><Gitlab class="h-5 w-5" /></a>
                        <a v-if="settings?.email" :href="`mailto:${settings.email}`" aria-label="Email" class="flex h-12 w-12 items-center justify-center rounded-xl border border-(--line) bg-(--overlay) text-(--muted) hover:border-(--accent)/50 hover:text-(--accent-soft)"><Mail class="h-5 w-5" /></a>
                    </div>
                </div>

                <p v-if="locationText" class="mt-6 flex items-center gap-2 text-sm text-(--faint)">
                    <MapPin class="h-4 w-4 text-(--accent)" /> {{ locationText }}
                </p>
            </div>

            <div class="relative order-1 lg:order-2 h-[340px] sm:h-[420px] lg:h-[600px]">
                <div class="absolute inset-[12%] rounded-full bg-(--accent)/10 blur-3xl"></div>
                <ThreeStage placement="hero" particles :camera-z="7">
                    <template #fallback>
                        <div class="flex h-full items-center justify-center">
                            <img v-if="avatar" :src="avatar" :alt="`${firstName} ${lastName}`" class="h-72 w-72 rounded-full object-cover gold-ring" />
                        </div>
                    </template>
                </ThreeStage>
            </div>
        </div>

        <!-- Tech marquee -->
        <div class="relative border-y border-(--line-soft) bg-(--bg-soft)/60 py-4 overflow-hidden" dir="ltr">
            <div class="marquee flex w-max gap-10 whitespace-nowrap font-mono text-sm text-(--faint)">
                <span v-for="(tech, i) in [...stack, ...stack]" :key="i" class="flex items-center gap-10">
                    {{ tech }} <span class="text-(--accent)">◆</span>
                </span>
            </div>
        </div>
    </section>
</template>
