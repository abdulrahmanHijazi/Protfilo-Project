<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Send, Mail, Phone, MapPin, Loader2, CheckCircle2 } from 'lucide-vue-next';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import Input from '@/components/ui/Input.vue';
import Textarea from '@/components/ui/Textarea.vue';
import ThreeStage from '@/components/three/ThreeStage.vue';

const { t, locale } = useI18n();
const page = usePage();
const loc = computed(() => page.props.locale || locale.value || 'en');
const props = defineProps({ settings: { type: Object, default: () => ({}) } });

const locationText = computed(() => {
    const v = props.settings?.location;
    if (v && typeof v === 'object') return v?.[loc.value] ?? v?.en ?? '';
    return v ?? '';
});

const form = useForm({ name: '', email: '', subject: '', message: '' });
const sent = ref(false);

function contactPostUrl() {
    try {
        if (typeof route === 'function') return route('contact.store');
    } catch (e) {}
    return '/contact';
}

function submit() {
    sent.value = false;
    form.post(contactPostUrl(), {
        preserveScroll: true,
        onSuccess: () => { sent.value = true; form.reset(); },
    });
}
</script>

<template>
    <section id="contact" class="mx-auto max-w-7xl px-4 sm:px-6 py-20">
        <SectionHeading :eyebrow="t('contact.eyebrow')" :title="t('contact.title')" :subtitle="t('contact.subtitle')" />
        <div class="grid gap-8 lg:grid-cols-5">
            <div v-reveal class="relative overflow-hidden lg:col-span-2 rounded-2xl border border-(--accent)/20 bg-gradient-to-b from-(--accent)/10 to-transparent p-8">
                <div class="pointer-events-none absolute -bottom-16 -end-16 h-64 w-64 opacity-90"><ThreeStage placement="contact" :camera-z="6" :hint="false" /></div>
                <h3 class="font-bold text-(--text)">{{ t('contact.info_title') }}</h3>
                <ul class="relative mt-5 space-y-4 text-sm text-(--muted)">
                    <li v-if="settings?.email" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft)"><Mail class="h-4 w-4" /></span>
                        <a :href="`mailto:${settings.email}`" class="hover:text-(--accent-soft) break-all">{{ settings.email }}</a>
                    </li>
                    <li v-if="settings?.phone" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft)"><Phone class="h-4 w-4" /></span>
                        <a :href="`tel:${settings.phone}`" dir="ltr" class="hover:text-(--accent-soft)">{{ settings.phone }}</a>
                    </li>
                    <li v-if="locationText" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-(--accent)/15 text-(--accent-soft)"><MapPin class="h-4 w-4" /></span>
                        {{ locationText }}
                    </li>
                </ul>
                <a v-if="settings?.whatsapp" :href="`https://wa.me/${String(settings.whatsapp).replace(/[^0-9]/g, '')}`" target="_blank"
                    class="relative mt-6 inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#25D366]/15 border border-[#25D366]/30 px-6 text-sm font-bold text-[#4be07f] hover:bg-[#25D366]/25">
                    {{ t('contact.whatsapp') }}
                </a>
            </div>

            <form v-reveal="120" @submit.prevent="submit" class="lg:col-span-3 rounded-2xl border border-(--line) bg-(--bg-2) p-8 space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-(--muted)">{{ t('contact.name') }}</label>
                        <Input v-model="form.name" :placeholder="t('contact.name_placeholder')" required />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-(--muted)">{{ t('contact.email') }}</label>
                        <Input v-model="form.email" type="email" :placeholder="t('contact.email_placeholder')" required />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-(--muted)">{{ t('contact.subject') }}</label>
                    <Input v-model="form.subject" :placeholder="t('contact.subject_placeholder')" />
                    <p v-if="form.errors.subject" class="mt-1 text-xs text-red-400">{{ form.errors.subject }}</p>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-(--muted)">{{ t('contact.message') }}</label>
                    <Textarea v-model="form.message" :placeholder="t('contact.message_placeholder')" required />
                    <p v-if="form.errors.message" class="mt-1 text-xs text-red-400">{{ form.errors.message }}</p>
                </div>
                <p v-if="sent" class="flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    <CheckCircle2 class="h-4 w-4" /> {{ t('contact.success') }}
                </p>
                <button type="submit" :disabled="form.processing"
                    class="inline-flex h-11 items-center gap-2 rounded-xl bg-gradient-to-l from-(--accent) to-(--accent-soft) px-6 text-sm font-bold text-(--on-accent) shadow-lg shadow-(--accent)/25 hover:brightness-110 disabled:opacity-50">
                    <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <Send v-else class="h-4 w-4 rtl:rotate-180" />
                    {{ form.processing ? t('contact.sending') : t('contact.send_message') }}
                </button>
            </form>
        </div>
    </section>
</template>
