<script setup>
import { cn } from '@/lib/utils';

defineProps({ modelValue: [String, Number], placeholder: String, name: String, rows: { type: [Number, String], default: 5 }, required: Boolean });
defineEmits(['update:modelValue']);
</script>

<template>
    <textarea
        :name="name"
        :required="required"
        :rows="rows"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="cn('flex w-full rounded-xl border border-(--line) bg-(--overlay) px-4 py-3 text-sm text-(--text) placeholder:text-(--faint) focus:border-(--accent)/60 focus:outline-none focus:ring-2 focus:ring-(--accent)/20')"
    />
</template>
