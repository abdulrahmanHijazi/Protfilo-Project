<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { MessageCircle } from 'lucide-vue-next';

const { t } = useI18n();
const props = defineProps({ phone: { type: String, default: '' } });

const href = computed(() => {
    const digits = String(props.phone || '').replace(/[^0-9]/g, '');
    if (!digits) return null;
    return `https://wa.me/${digits}`;
});
</script>

<template>
    <a v-if="href" :href="href" target="_blank" :title="t('contact.whatsapp')"
        class="fixed bottom-6 end-6 z-50 flex h-13 w-13 items-center justify-center rounded-full bg-[#25D366] p-3.5 text-white shadow-2xl shadow-[#25D366]/40 transition-transform hover:scale-110">
        <MessageCircle class="h-6 w-6" />
    </a>
</template>
