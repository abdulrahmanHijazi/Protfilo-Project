<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { Briefcase, MapPin, CheckCircle2 } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import Badge from '@/components/ui/Badge.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
defineProps({ experiences: { type: Array, default: () => [] } });

const tr = (v) => (v && typeof v === 'object' ? v?.[loc.value] ?? v?.en ?? '' : v ?? '');
const lines = (v) => tr(v).split('\n').map((s) => s.trim()).filter(Boolean);

function period(e) {
    const fmt = (d) => (d ? new Date(d).toLocaleDateString(loc.value === 'ar' ? 'ar' : 'en', { month: 'short', year: 'numeric' }) : '');
    return `${fmt(e.start_date)} — ${e.current ? t('experience.present') : fmt(e.end_date)}`;
}
</script>

<template>
    <section v-if="experiences?.length" id="experience" class="relative border-y border-(--line-soft) bg-(--bg-soft)">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 py-20">
            <SectionHeading :eyebrow="t('experience.eyebrow')" :title="t('experience.title')" :subtitle="t('experience.subtitle')" />
            <ol class="relative space-y-8 before:absolute before:start-[19px] before:top-2 before:bottom-2 before:w-px before:bg-gradient-to-b before:from-(--accent) before:via-(--accent)/30 before:to-transparent">
                <li v-for="(e, i) in experiences" :key="e.id" v-reveal="i * 60" class="relative ps-14">
                    <span :class="['absolute start-0 top-1 flex h-10 w-10 items-center justify-center rounded-full border bg-(--bg-2)',
                        e.current ? 'border-(--accent) text-(--accent-soft) shadow-[0_0_24px_var(--glow)]' : 'border-(--line-strong) text-(--muted)']">
                        <Briefcase class="h-4 w-4" />
                    </span>
                    <div class="rounded-2xl border border-(--line) bg-(--bg-2) p-6 transition-colors hover:border-(--accent)/40">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h3 class="text-lg font-bold text-(--text)">{{ tr(e.title) }}</h3>
                                <p class="text-sm font-semibold text-(--accent-soft)">{{ e.company }}</p>
                            </div>
                            <div class="text-end">
                                <p class="font-mono text-xs text-(--muted)" dir="auto">{{ period(e) }}</p>
                                <p v-if="tr(e.location)" class="mt-1 flex items-center justify-end gap-1 text-xs text-(--faint)"><MapPin class="h-3 w-3" /> {{ tr(e.location) }}</p>
                            </div>
                        </div>
                        <p v-if="tr(e.description)" class="mt-3 text-sm leading-relaxed text-(--muted)">{{ tr(e.description) }}</p>
                        <ul v-if="lines(e.highlights).length" class="mt-4 space-y-2">
                            <li v-for="(h, j) in lines(e.highlights)" :key="j" class="flex gap-2 text-sm text-(--muted)">
                                <CheckCircle2 class="mt-0.5 h-4 w-4 shrink-0 text-(--accent)" /> <span>{{ h }}</span>
                            </li>
                        </ul>
                        <div v-if="e.techs?.length" class="mt-4 flex flex-wrap gap-1.5" dir="ltr">
                            <Badge v-for="tech in e.techs" :key="tech" variant="secondary">{{ tech }}</Badge>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </section>
</template>
