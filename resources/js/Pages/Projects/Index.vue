<script setup>
import AppLayout from '@/components/Layout/AppLayout.vue';
import ProjectsGrid from '@/components/sections/ProjectsGrid.vue';

defineProps({
    settings: { type: Object, default: () => ({}) },
    projects: { type: Array, default: () => [] },
    locale: String,
    dir: String,
});
</script>

<template>
    <AppLayout :settings="settings">
        <div class="pt-10">
            <ProjectsGrid :projects="projects" :limit="projects?.length || 0" />
        </div>
    </AppLayout>
</template>
