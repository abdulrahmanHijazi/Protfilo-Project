<script setup>
import { computed } from 'vue';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const props = defineProps({ variant: { type: String, default: 'default' } });

const badgeVariants = cva(
    'inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-medium transition-colors',
    {
        variants: {
            variant: {
                default: 'border-(--accent)/30 bg-(--accent)/10 text-(--accent-soft)',
                secondary: 'border-(--line) bg-(--overlay) text-(--muted)',
                outline: 'border-(--line-strong) text-(--text)',
            },
        },
        defaultVariants: { variant: 'default' },
    }
);

const classes = computed(() => cn(badgeVariants({ variant: props.variant })));
</script>

<template>
    <span :class="classes"><slot /></span>
</template>
