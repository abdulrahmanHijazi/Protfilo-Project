<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import { Award, ExternalLink } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import Card from '@/components/ui/Card.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
defineProps({ certificates: { type: Array, default: () => [] } });

function fieldOf(c, key) {
    const v = c?.[key];
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
}
</script>

<template>
    <section id="certificates" class="border-y border-(--line-soft) bg-(--bg-soft)">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-20">
            <SectionHeading :eyebrow="t('certificates.eyebrow')" :title="t('certificates.title')" :subtitle="t('certificates.subtitle')" />
            <p v-if="!certificates?.length" class="text-center text-(--faint)">{{ t('certificates.empty') }}</p>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="c in certificates" :key="c.id" class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft)">
                            <Award class="h-5 w-5" />
                        </div>
                        <a v-if="c.credential_url || c.url" :href="c.credential_url || c.url" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-(--accent-soft) hover:underline">
                            {{ t('certificates.verify') }} <ExternalLink class="h-3 w-3" />
                        </a>
                    </div>
                    <h3 class="mt-4 font-bold text-(--text)">{{ fieldOf(c, 'title') || fieldOf(c, 'name') }}</h3>
                    <p class="mt-1 text-sm text-(--muted)">{{ fieldOf(c, 'issuer') || fieldOf(c, 'organization') }}</p>
                    <p v-if="c.date || c.issued_at" class="mt-2 text-xs text-(--faint)">{{ c.date || c.issued_at }}</p>
                </Card>
            </div>
        </div>
    </section>
</template>
