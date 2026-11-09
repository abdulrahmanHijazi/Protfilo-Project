<script setup>
import { computed } from 'vue';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const props = defineProps({
    variant: { type: String, default: 'default' },
    size: { type: String, default: 'default' },
    as: { type: String, default: 'button' },
    href: { type: String, default: null },
});

const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)/60 disabled:pointer-events-none disabled:opacity-50 cursor-pointer',
    {
        variants: {
            variant: {
                default: 'bg-gradient-to-l from-(--accent) to-(--accent-soft) text-(--on-accent) shadow-lg shadow-(--accent)/25 hover:shadow-(--accent)/40 hover:brightness-110',
                secondary: 'bg-(--overlay) text-(--text) border border-(--line) hover:bg-(--overlay-strong) hover:border-(--accent)/40',
                outline: 'border border-(--accent)/50 text-(--accent-soft) hover:bg-(--accent)/10',
                ghost: 'text-(--muted) hover:text-(--accent-soft) hover:bg-(--overlay)',
                link: 'text-(--accent-soft) underline-offset-4 hover:underline',
            },
            size: {
                default: 'h-11 px-6 py-2',
                sm: 'h-9 px-4 text-xs',
                lg: 'h-13 px-8 py-3.5 text-base',
                icon: 'h-10 w-10',
            },
        },
        defaultVariants: { variant: 'default', size: 'default' },
    }
);

const classes = computed(() => cn(buttonVariants({ variant: props.variant, size: props.size }), props.class));
</script>

<template>
    <a v-if="as === 'a' || href" :href="href" :class="classes"><slot /></a>
    <button v-else :class="classes"><slot /></button>
</template>
