<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/components/Layout/AppLayout.vue';
import Hero from '@/components/sections/Hero.vue';
import Stats from '@/components/sections/Stats.vue';
import About from '@/components/sections/About.vue';
import ExperienceTimeline from '@/components/sections/ExperienceTimeline.vue';
import ServicesGrid from '@/components/sections/ServicesGrid.vue';
import SkillsBars from '@/components/sections/SkillsBars.vue';
import ProjectsGrid from '@/components/sections/ProjectsGrid.vue';
import Timeline from '@/components/sections/Timeline.vue';
import CertificatesGrid from '@/components/sections/CertificatesGrid.vue';
import ContactForm from '@/components/sections/ContactForm.vue';
import WhatsAppButton from '@/components/sections/WhatsAppButton.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    scenes: { type: Object, default: () => ({}) },
    experiences: { type: Array, default: () => [] },
    projects: { type: Array, default: () => [] },
    services: { type: Array, default: () => [] },
    skills: { type: Array, default: () => [] },
    languages: { type: Array, default: () => [] },
    certificates: { type: Array, default: () => [] },
    education: { type: Array, default: () => [] },
    locale: String,
    dir: String,
});

const counts = computed(() => ({
    projects: props.projects.length,
    vega: props.projects.filter((p) => p.company === 'Vega').length,
    certificates: props.certificates.length,
}));
const seoTitle = computed(() => props.settings?.seo_title?.[props.locale] || props.settings?.seo_title?.en || '');
</script>

<template>
    <Head :title="seoTitle" />
    <AppLayout :settings="settings">
        <Hero :settings="settings" :experiences="experiences" />
        <Stats :settings="settings" :counts="counts" />
        <About :settings="settings" />
        <ExperienceTimeline :experiences="experiences" />
        <ProjectsGrid :projects="projects" :limit="9" />
        <SkillsBars :skills="skills" :languages="languages" />
        <ServicesGrid :services="services" />
        <Timeline :education="education" />
        <CertificatesGrid :certificates="certificates" />
        <ContactForm :settings="settings" />
        <WhatsAppButton :phone="settings?.whatsapp || settings?.phone" />
    </AppLayout>
</template>
