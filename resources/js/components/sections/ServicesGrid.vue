<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import * as icons from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import Card from '@/components/ui/Card.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
defineProps({ services: { type: Array, default: () => [] } });

function titleOf(s) {
    if (!s) return '';
    if (typeof s.title === 'object') return s.title?.[loc.value] ?? s.title?.en ?? '';
    return s.title ?? '';
}
function descOf(s) {
    if (!s) return '';
    if (typeof s.description === 'object') return s.description?.[loc.value] ?? s.description?.en ?? '';
    return s.description ?? '';
}
function iconFor(s) {
    const name = s?.icon || 'Sparkles';
    return icons[name] || icons.Sparkles;
}
</script>

<template>
    <section id="services" class="mx-auto max-w-7xl px-4 sm:px-6 py-20">
        <SectionHeading :eyebrow="t('services.eyebrow')" :title="t('services.title')" :subtitle="t('services.subtitle')" />
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <Card v-for="s in services" :key="s.id" class="group p-7 hover:-translate-y-1">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft) group-hover:bg-(--accent) group-hover:text-(--on-accent) transition-colors">
                    <component :is="iconFor(s)" class="h-6 w-6" />
                </div>
                <h3 class="mt-4 font-bold text-(--text)">{{ titleOf(s) }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-(--muted)">{{ descOf(s) }}</p>
            </Card>
        </div>
    </section>
</template>
