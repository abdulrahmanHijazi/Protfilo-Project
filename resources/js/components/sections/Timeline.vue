<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { GraduationCap } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
defineProps({ education: { type: Array, default: () => [] } });

function year(d) {
    return d ? new Date(d).getFullYear() : '';
}
function fieldOf(e, key) {
    const v = e?.[key];
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
}
</script>

<template>
    <section id="education" class="mx-auto max-w-4xl px-4 sm:px-6 py-20">
        <SectionHeading :eyebrow="t('education.eyebrow')" :title="t('education.title')" :subtitle="t('education.subtitle')" />
        <div class="relative space-y-6 before:absolute before:start-[19px] before:top-2 before:bottom-2 before:w-px before:bg-gradient-to-b before:from-(--accent)/60 before:to-transparent">
            <div v-for="(e, i) in education" :key="e.id" v-reveal="i * 80" class="relative ps-14">
                <span class="absolute start-2 top-1 flex h-9 w-9 items-center justify-center rounded-full border border-(--accent)/40 bg-(--bg-2) text-(--accent-soft)">
                    <GraduationCap class="h-4 w-4" />
                </span>
                <div class="rounded-2xl border border-(--line) bg-(--bg-2) p-6 hover:border-(--accent)/40 transition-colors">
                    <p v-if="e.start_date" dir="ltr" class="text-xs font-bold uppercase tracking-wider text-(--accent)">
                        {{ e.current ? `${year(e.start_date)} — ${t('experience.present')}` : `${year(e.start_date)} — ${year(e.end_date)}` }}
                    </p>
                    <h3 class="mt-1 font-bold text-(--text)">{{ fieldOf(e, 'degree') }}<span v-if="fieldOf(e, 'field')" class="font-normal text-(--muted)"> — {{ fieldOf(e, 'field') }}</span></h3>
                    <p class="text-sm text-(--muted)">{{ fieldOf(e, 'school') || fieldOf(e, 'institution') }}</p>
                    <p v-if="fieldOf(e, 'description')" class="mt-2 text-sm leading-relaxed text-(--muted)">{{ fieldOf(e, 'description') }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
