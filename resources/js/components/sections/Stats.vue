<script setup>
import { useI18n } from 'vue-i18n';
import { Briefcase, Award, Building2, FolderGit2 } from 'lucide-vue-next';
const { t } = useI18n();
defineProps({ settings: { type: Object, default: () => ({}) }, counts: { type: Object, default: () => ({}) } });
</script>

<template>
    <section class="mx-auto max-w-7xl px-4 sm:px-6 py-14">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="(item, i) in [
                    { icon: FolderGit2, value: `${counts.projects ?? 0}+`, label: t('stats.projects') },
                    { icon: Briefcase, value: `${settings?.years_experience ?? 0}+`, label: t('stats.experience') },
                    { icon: Building2, value: counts.vega ?? 0, label: t('stats.vega_projects') },
                    { icon: Award, value: counts.certificates ?? 0, label: t('stats.certificates') },
                ]" :key="i" v-reveal="i * 80" v-tilt="6"
                class="relative overflow-hidden rounded-2xl border border-(--line) bg-(--bg-2) p-6 hover:border-(--accent)/40">
                <div class="tilt-glare pointer-events-none absolute inset-0"></div>
                <component :is="item.icon" class="h-6 w-6 text-(--accent)" />
                <p class="mt-4 text-4xl font-black text-(--text) tilt-pop">{{ item.value }}</p>
                <p class="mt-1 text-sm text-(--muted)">{{ item.label }}</p>
            </div>
        </div>
    </section>
</template>
