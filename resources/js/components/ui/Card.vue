<script setup>
import { cn } from '@/lib/utils';
</script>

<template>
    <div :class="cn('rounded-2xl border border-(--line) bg-(--bg-2)/90 backdrop-blur transition-all duration-300 hover:border-(--accent)/40 hover:gold-ring', $attrs.class)">
        <slot />
    </div>
</template>
