<script setup>
import { useId } from 'vue';

// The "Gold gem" AH mark. Facets are mixed from --accent so the logo follows
// Site Settings → Appearance; the static files in /public/brand keep the gold originals.
defineProps({ size: { type: [Number, String], default: 36 } });
const gradientId = `ah-gold-${useId()}`;

const facets = [
    ['M32 32 32 3 57 17.5Z', 'color-mix(in srgb, var(--accent) 55%, white)'],
    ['M32 32 57 17.5V46.5Z', 'var(--accent)'],
    ['M32 32 57 46.5 32 61Z', 'color-mix(in srgb, var(--accent) 62%, black)'],
    ['M32 32 32 61 7 46.5Z', 'color-mix(in srgb, var(--accent) 78%, black)'],
    ['M32 32 7 46.5V17.5Z', 'color-mix(in srgb, var(--accent) 88%, white)'],
    ['M32 32 7 17.5 32 3Z', 'color-mix(in srgb, var(--accent) 72%, white)'],
];
</script>

<template>
    <svg :width="size" :height="size" viewBox="0 0 64 64" role="img" aria-label="AH logo" class="shrink-0">
        <defs>
            <linearGradient :id="gradientId" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" style="stop-color: color-mix(in srgb, var(--accent) 40%, white)" />
                <stop offset=".45" style="stop-color: var(--accent)" />
                <stop offset="1" style="stop-color: color-mix(in srgb, var(--accent) 70%, black)" />
            </linearGradient>
        </defs>
        <path v-for="[d, fill] in facets" :key="d" :d="d" :style="{ fill }" />
        <path d="M32 8 53 20v24L32 56 11 44V20Z" fill="#0E0E11" opacity=".9" />
        <path d="M17.5 21v22M46.5 21v22M25 43 32 19.5 39 43M17.5 35h29" fill="none" :stroke="`url(#${gradientId})`"
            stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
</template>
