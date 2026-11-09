<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { Server, Database, Plug, Monitor, Smartphone, Wrench, Sparkles, Languages } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import ThreeStage from '@/components/three/ThreeStage.vue';

const { t, te, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
const props = defineProps({ skills: { type: Array, default: () => [] }, languages: { type: Array, default: () => [] } });

const ICONS = { backend: Server, database: Database, integrations: Plug, frontend: Monitor, mobile: Smartphone, tools: Wrench };
const tr = (v) => (v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '');

const groups = computed(() => {
    const map = new Map();
    props.skills.forEach((s) => {
        const key = s.category || 'other';
        if (!map.has(key)) map.set(key, []);
        map.get(key).push(s);
    });
    return [...map.entries()].map(([key, items]) => ({
        key,
        icon: ICONS[key] || Sparkles,
        label: te(`skills.categories.${key}`) ? t(`skills.categories.${key}`) : key,
        items,
    }));
});
const hasScene = computed(() => (page.props.scenes?.skills ?? []).length > 0);
</script>

<template>
    <section id="skills" class="relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-20">
            <div class="grid items-center gap-6 lg:grid-cols-[1fr_320px]">
                <SectionHeading :eyebrow="t('skills.eyebrow')" :title="t('skills.title')" :subtitle="t('skills.subtitle')" align="start" class="!mb-0" />
                <div v-if="hasScene" class="hidden lg:block h-64"><ThreeStage placement="skills" :camera-z="5.5" /></div>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="(g, i) in groups" :key="g.key" v-reveal="i * 70" v-tilt="5"
                    class="relative overflow-hidden rounded-2xl border border-(--line) bg-(--bg-2) p-6 hover:border-(--accent)/40">
                    <div class="tilt-glare pointer-events-none absolute inset-0"></div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft)"><component :is="g.icon" class="h-5 w-5" /></span>
                        <h3 class="font-bold text-(--text)">{{ g.label }}</h3>
                    </div>
                    <ul class="mt-5 space-y-3.5" dir="ltr">
                        <li v-for="s in g.items" :key="s.id">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-(--text)">{{ tr(s.name) }}</span>
                                <span class="font-mono text-xs text-(--accent-soft)">{{ s.level }}%</span>
                            </div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-(--overlay-strong)" role="progressbar" :aria-valuenow="s.level" aria-valuemin="0" aria-valuemax="100">
                                <div class="h-full rounded-full bg-gradient-to-r from-(--accent-deep) via-(--accent) to-(--accent-soft)" :style="{ width: `${s.level}%` }"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div v-if="languages?.length" v-reveal class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <span class="flex items-center gap-2 text-sm font-bold text-(--text)"><Languages class="h-4 w-4 text-(--accent)" /> {{ t('skills.languages_title') }}</span>
                <span v-for="l in languages" :key="l.id" class="rounded-full border border-(--line) bg-(--overlay) px-4 py-1.5 text-sm text-(--muted)">
                    {{ tr(l.name) }} · <span class="text-(--accent-soft)">{{ t(`languages.levels.${l.proficiency}`) }}</span>
                </span>
            </div>
        </div>
    </section>
</template>
