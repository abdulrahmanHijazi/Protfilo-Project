<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { Mail, Phone, MapPin, Download, Linkedin, Gitlab } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import ThreeStage from '@/components/three/ThreeStage.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
const props = defineProps({ settings: { type: Object, default: () => ({}) } });
const tr = (v) => (v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '');
const about = computed(() => tr(props.settings?.about_us));
const locationText = computed(() => tr(props.settings?.location));
const fullName = computed(() => `${tr(props.settings?.first_name)} ${tr(props.settings?.last_name)}`.trim());
const avatar = computed(() => props.settings?.avatar_url || null);

const info = computed(() => [
    props.settings?.email && { icon: Mail, label: t('about.email'), value: props.settings.email, href: `mailto:${props.settings.email}` },
    props.settings?.phone && { icon: Phone, label: t('about.phone'), value: props.settings.phone, href: `tel:${props.settings.phone}`, ltr: true },
    locationText.value && { icon: MapPin, label: t('about.location'), value: locationText.value },
    props.settings?.linkedin && { icon: Linkedin, label: 'LinkedIn', value: props.settings.linkedin.replace(/^https?:\/\/(www\.)?/, ''), href: props.settings.linkedin, ltr: true },
    props.settings?.gitlab && { icon: Gitlab, label: 'GitLab', value: props.settings.gitlab.replace(/^https?:\/\/(www\.)?/, ''), href: props.settings.gitlab, ltr: true },
].filter(Boolean));
</script>

<template>
    <section id="about" class="mx-auto max-w-7xl px-4 sm:px-6 py-20">
        <SectionHeading :eyebrow="t('about.eyebrow')" :title="t('about.title')" />
        <div class="grid items-center gap-10 lg:grid-cols-[420px_1fr]">
            <div v-reveal class="relative mx-auto h-[380px] w-full max-w-[420px]">
                <div class="absolute end-0 -top-8 h-56 w-56 sm:h-64 sm:w-64">
                    <ThreeStage placement="about" :camera-z="6" :hint="false" />
                </div>
                <div class="absolute bottom-0 start-0 h-72 w-72 sm:h-80 sm:w-80 overflow-hidden rounded-[2rem] border border-(--accent)/30 bg-gradient-to-br from-(--bg-3) to-(--bg) gold-ring">
                    <img v-if="avatar" :src="avatar" :alt="fullName" class="h-full w-full object-cover" />
                    <div v-else class="flex h-full items-center justify-center text-7xl font-black text-gold-gradient">{{ fullName.charAt(0) }}</div>
                </div>
                <div class="glass absolute bottom-6 end-0 z-10 rounded-2xl border border-(--line) px-4 py-3 shadow-xl">
                    <p class="text-3xl font-black text-gold-gradient">{{ settings?.years_experience ?? 3 }}+</p>
                    <p class="text-xs text-(--muted)">{{ t('hero.years_experience') }}</p>
                </div>
            </div>

            <div v-reveal="120">
                <p class="whitespace-pre-line text-lg leading-loose text-(--muted)">{{ about }}</p>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    <component :is="item.href ? 'a' : 'div'" v-for="item in info" :key="item.label" :href="item.href"
                        :target="item.href?.startsWith('http') ? '_blank' : undefined"
                        class="flex items-center gap-3 rounded-xl border border-(--line) bg-(--bg-2) p-3 transition-colors hover:border-(--accent)/40">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-(--accent)/15 text-(--accent-soft)"><component :is="item.icon" class="h-4 w-4" /></span>
                        <span class="min-w-0">
                            <span class="block text-[11px] uppercase tracking-wider text-(--faint)">{{ item.label }}</span>
                            <span class="block truncate text-sm text-(--text)" :dir="item.ltr ? 'ltr' : undefined">{{ item.value }}</span>
                        </span>
                    </component>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a v-if="settings?.cv_url" :href="settings.cv_url" target="_blank" class="inline-flex h-11 items-center gap-2 rounded-xl bg-gradient-to-l from-(--accent) to-(--accent-soft) px-6 text-sm font-bold text-(--on-accent) shadow-lg shadow-(--accent)/25 hover:brightness-110">
                        <Download class="h-4 w-4" /> {{ t('about.download_cv') }}
                    </a>
                    <a href="#contact" class="inline-flex h-11 items-center rounded-xl border border-(--line) bg-(--overlay) px-6 text-sm font-bold hover:border-(--accent)/50 hover:text-(--accent-soft)">
                        {{ t('about.hire_me') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
