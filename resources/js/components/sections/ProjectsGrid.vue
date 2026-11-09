<script setup>
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Globe, Star } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import ThreeStage from '@/components/three/ThreeStage.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
const props = defineProps({ projects: { type: Array, default: () => [] }, limit: { type: Number, default: 9 } });

const tr = (v) => (v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '');
const techsOf = (p) => (Array.isArray(p.technologies ?? p.techs) ? p.technologies ?? p.techs : []);
const coverOf = (p) => p.image || p.cover || null;
const projectHref = (p) => `/projects/${p.slug || p.id}`;

const companies = computed(() => {
    const counts = {};
    props.projects.forEach((p) => {
        if (p.company) counts[p.company] = (counts[p.company] || 0) + 1;
    });
    return Object.entries(counts).sort((a, b) => b[1] - a[1]).map(([name, count]) => ({ name, count }));
});

const active = ref('all');
const showAll = ref(false);
const filtered = computed(() => {
    const list = active.value === 'all' ? props.projects : props.projects.filter((p) => p.company === active.value);
    // Featured first on the "all" tab so the strongest work leads.
    return active.value === 'all' ? [...list].sort((a, b) => Number(b.featured) - Number(a.featured)) : list;
});
const visible = computed(() => (showAll.value || !props.limit ? filtered.value : filtered.value.slice(0, props.limit)));
const hasScene = computed(() => (page.props.scenes?.projects ?? []).length > 0);
</script>

<template>
    <section id="projects" class="relative mx-auto max-w-7xl px-4 sm:px-6 py-20">
        <div class="grid items-center gap-6 lg:grid-cols-[1fr_320px]">
            <SectionHeading :eyebrow="t('projects.eyebrow')" :title="t('projects.title')" :subtitle="t('projects.subtitle')" align="start" class="!mb-0" />
            <div v-if="hasScene" class="hidden lg:block h-56"><ThreeStage placement="projects" :camera-z="6" /></div>
        </div>

        <div class="mt-10 mb-8 flex flex-wrap gap-2">
            <button type="button" @click="active = 'all'; showAll = false"
                :class="['rounded-full border px-4 py-2 text-sm font-semibold transition-colors', active === 'all' ? 'border-(--accent) bg-(--accent) text-(--on-accent)' : 'border-(--line) bg-(--overlay) text-(--muted) hover:text-(--text)']">
                {{ t('projects.filter_all') }} <span class="opacity-70">({{ projects.length }})</span>
            </button>
            <button v-for="c in companies" :key="c.name" type="button" @click="active = c.name; showAll = false"
                :class="['rounded-full border px-4 py-2 text-sm font-semibold transition-colors', active === c.name ? 'border-(--accent) bg-(--accent) text-(--on-accent)' : 'border-(--line) bg-(--overlay) text-(--muted) hover:text-(--text)']">
                {{ c.name }} <span class="opacity-70">({{ c.count }})</span>
            </button>
        </div>

        <p v-if="!projects?.length" class="text-center text-(--faint)">{{ t('projects.empty') }}</p>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <a v-for="(p, i) in visible" :key="p.id" :href="projectHref(p)" v-reveal="(i % 3) * 80" v-tilt="7"
                :style="{ '--card-accent': p.accent_color || 'var(--accent)' }"
                class="group relative flex flex-col overflow-hidden rounded-2xl border border-(--line) bg-(--bg-2) hover:border-(--card-accent)/60">
                <div class="tilt-glare pointer-events-none absolute inset-0 z-10"></div>
                <div class="relative h-40 overflow-hidden">
                    <img v-if="coverOf(p)" :src="coverOf(p)" :alt="tr(p.title)" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" />
                    <div v-else class="relative h-full w-full bg-(--bg-3)"
                        :style="{ backgroundImage: `radial-gradient(circle at 30% 20%, color-mix(in srgb, ${p.accent_color || '#C9A227'} 45%, transparent), transparent 60%), radial-gradient(circle at 80% 90%, color-mix(in srgb, ${p.accent_color || '#C9A227'} 25%, transparent), transparent 55%)` }">
                        <div class="bg-grid absolute inset-0 opacity-70"></div>
                        <span class="tilt-pop absolute inset-0 flex items-center justify-center text-5xl font-black tracking-tight text-white/90 drop-shadow-lg">
                            {{ tr(p.title).split(' ').slice(0, 2).map((w) => w.charAt(0)).join('') }}
                        </span>
                    </div>
                    <span v-if="p.company" class="absolute top-3 start-3 z-10 rounded-full bg-black/55 px-2.5 py-1 text-[11px] font-bold text-white backdrop-blur">{{ p.company }}</span>
                    <span v-if="p.featured" class="absolute top-3 end-3 z-10 flex h-7 w-7 items-center justify-center rounded-full bg-black/55 text-amber-300 backdrop-blur"><Star class="h-3.5 w-3.5 fill-current" /></span>
                </div>
                <div class="flex flex-1 flex-col p-6">
                    <p v-if="tr(p.category)" class="text-[11px] font-bold uppercase tracking-wider" :style="{ color: p.accent_color || 'var(--accent)' }">{{ tr(p.category) }}</p>
                    <h3 class="mt-1 text-lg font-bold text-(--text)">{{ tr(p.title) }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-(--muted)">{{ tr(p.short_desc) || tr(p.description) }}</p>
                    <div v-if="techsOf(p).length" class="mt-4 flex flex-wrap gap-1.5" dir="ltr">
                        <span v-for="tech in techsOf(p).slice(0, 5)" :key="tech" class="rounded-md border border-(--line) bg-(--overlay) px-2 py-0.5 font-mono text-[11px] text-(--muted)">{{ tech }}</span>
                    </div>
                    <div class="mt-auto flex items-center gap-3 pt-5 text-xs">
                        <span class="inline-flex items-center gap-1 font-bold text-(--accent-soft) group-hover:underline">
                            {{ t('projects.view_project') }} <ArrowUpRight class="h-3.5 w-3.5 rtl:-scale-x-100" />
                        </span>
                        <span v-if="p.live_url" class="inline-flex items-center gap-1 text-(--muted)"><Globe class="h-3.5 w-3.5" /> {{ t('projects.live_demo') }}</span>
                    </div>
                </div>
            </a>
        </div>

        <div v-if="limit && filtered.length > limit && !showAll" class="mt-10 text-center">
            <button type="button" @click="showAll = true" class="inline-flex h-11 items-center rounded-xl border border-(--accent)/40 px-6 text-sm font-bold text-(--accent-soft) hover:bg-(--accent)/10">
                {{ t('projects.show_more', { count: filtered.length - limit }) }}
            </button>
        </div>
    </section>
</template>
