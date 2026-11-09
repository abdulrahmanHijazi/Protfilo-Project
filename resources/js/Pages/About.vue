<script setup>
import AppLayout from '@/components/Layout/AppLayout.vue';
import About from '@/components/sections/About.vue';
import ExperienceTimeline from '@/components/sections/ExperienceTimeline.vue';
import SkillsBars from '@/components/sections/SkillsBars.vue';
import Timeline from '@/components/sections/Timeline.vue';
import CertificatesGrid from '@/components/sections/CertificatesGrid.vue';

defineProps({
    settings: { type: Object, default: () => ({}) },
    experiences: { type: Array, default: () => [] },
    skills: { type: Array, default: () => [] },
    languages: { type: Array, default: () => [] },
    education: { type: Array, default: () => [] },
    educations: { type: Array, default: () => [] },
    certificates: { type: Array, default: () => [] },
    locale: String,
    dir: String,
});
</script>

<template>
    <AppLayout :settings="settings">
        <About :settings="settings" />
        <ExperienceTimeline :experiences="experiences" />
        <SkillsBars :skills="skills" :languages="languages" />
        <Timeline :education="education?.length ? education : educations" />
        <CertificatesGrid :certificates="certificates" />
    </AppLayout>
</template>
