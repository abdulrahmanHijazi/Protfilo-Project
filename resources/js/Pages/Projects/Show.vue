<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { ArrowLeft, Globe, Github, CheckCircle2 } from 'lucide-vue-next';
import AppLayout from '@/components/Layout/AppLayout.vue';
import Badge from '@/components/ui/Badge.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    project: { type: Object, default: () => ({}) },
    locale: String,
    dir: String,
});

const title = computed(() => {
    const v = props.project?.title;
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
});
const desc = computed(() => {
    const v = props.project?.description;
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
});
const techs = computed(() => {
    const v = props.project?.technologies ?? props.project?.techs;
    if (Array.isArray(v)) return v;
    if (typeof v === 'string') { try { return JSON.parse(v); } catch (e) { return v.split(',').map(s => s.trim()).filter(Boolean); } }
    return [];
});
const features = computed(() => {
    const v = props.project?.features;
    const text = v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '';
    return text.split('\n').map((s) => s.trim()).filter(Boolean);
});
const category = computed(() => {
    const v = props.project?.category;
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
});
const cover = computed(() => props.project?.image || props.project?.cover || props.project?.cover_url || null);
</script>

<template>
    <AppLayout :settings="settings">
        <article class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
            <a href="/projects" class="inline-flex items-center gap-2 text-sm text-(--muted) hover:text-(--accent-soft)">
                <ArrowLeft class="h-4 w-4 rtl:rotate-180" /> {{ t('projects.back_to_projects') }}
            </a>

            <div class="mt-6 flex flex-wrap items-center gap-2">
                <Badge v-if="project?.company">{{ project.company }}</Badge>
                <span v-if="category" class="text-xs font-bold uppercase tracking-wider" :style="{ color: project?.accent_color || 'var(--accent)' }">{{ category }}</span>
            </div>
            <h1 class="mt-3 text-3xl md:text-5xl font-black text-(--text)">{{ title }}</h1>

            <div v-if="techs.length" class="mt-4">
                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-(--faint)">{{ t('projects.technologies') }}</p>
                <div class="flex flex-wrap gap-2" dir="ltr">
                    <Badge v-for="tech in techs" :key="tech">{{ tech }}</Badge>
                </div>
            </div>

            <div v-if="cover" class="mt-6 overflow-hidden rounded-2xl border border-(--line)">
                <img :src="cover" :alt="title" class="w-full object-cover" />
            </div>

            <p class="mt-6 whitespace-pre-line leading-loose text-(--muted)">{{ desc }}</p>

            <div v-if="features.length" class="mt-8 rounded-2xl border border-(--line) bg-(--bg-2) p-6">
                <h2 class="font-bold text-(--text)">{{ t('projects.features') }}</h2>
                <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                    <li v-for="(f, i) in features" :key="i" class="flex gap-2 text-sm leading-relaxed text-(--muted)">
                        <CheckCircle2 class="mt-0.5 h-4 w-4 shrink-0" :style="{ color: project?.accent_color || 'var(--accent)' }" /> <span>{{ f }}</span>
                    </li>
                </ul>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a v-if="project?.live_url || project?.demo_url" :href="project.live_url || project.demo_url" target="_blank"
                    class="inline-flex h-11 items-center gap-2 rounded-xl bg-gradient-to-l from-(--accent) to-(--accent-soft) px-6 text-sm font-bold text-(--on-accent) shadow-lg shadow-(--accent)/25 hover:brightness-110">
                    <Globe class="h-4 w-4" /> {{ t('projects.live_demo') }}
                </a>
                <a v-if="project?.repo_url || project?.github_url" :href="project.repo_url || project.github_url" target="_blank"
                    class="inline-flex h-11 items-center gap-2 rounded-xl border border-(--line) bg-(--overlay) px-6 text-sm font-bold hover:border-(--accent)/50 hover:text-(--accent-soft)">
                    <Github class="h-4 w-4" /> {{ t('projects.source_code') }}
                </a>
            </div>
        </article>
    </AppLayout>
</template>
