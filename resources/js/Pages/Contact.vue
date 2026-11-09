<script setup>
import AppLayout from '@/components/Layout/AppLayout.vue';
import ContactForm from '@/components/sections/ContactForm.vue';
import WhatsAppButton from '@/components/sections/WhatsAppButton.vue';

defineProps({
    settings: { type: Object, default: () => ({}) },
    locale: String,
    dir: String,
});
</script>

<template>
    <AppLayout :settings="settings">
        <ContactForm :settings="settings" />
        <WhatsAppButton :phone="settings?.whatsapp || settings?.phone" />
    </AppLayout>
</template>
