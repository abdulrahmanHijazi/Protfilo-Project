<script setup>
defineProps({ eyebrow: String, title: String, subtitle: String, align: { type: String, default: 'center' } });
</script>

<template>
    <div :class="['max-w-2xl mb-12', align === 'center' ? 'mx-auto text-center' : 'text-start']">
        <p v-if="eyebrow" class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-(--accent)">
            <span class="h-px w-8 bg-(--accent)/60"></span>{{ eyebrow }}<span class="h-px w-8 bg-(--accent)/60"></span>
        </p>
        <h2 v-if="title" class="text-3xl md:text-4xl font-extrabold text-(--text)">{{ title }}</h2>
        <p v-if="subtitle" class="mt-4 text-(--muted) leading-relaxed">{{ subtitle }}</p>
    </div>
</template>
