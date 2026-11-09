<script setup>
import { cn } from '@/lib/utils';

defineProps({ modelValue: [String, Number], placeholder: String, type: { type: String, default: 'text' }, name: String, required: Boolean });
defineEmits(['update:modelValue']);
</script>

<template>
    <input
        :type="type"
        :name="name"
        :required="required"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="cn('flex h-11 w-full rounded-xl border border-(--line) bg-(--overlay) px-4 py-2 text-sm text-(--text) placeholder:text-(--faint) focus:border-(--accent)/60 focus:outline-none focus:ring-2 focus:ring-(--accent)/20')"
    />
</template>
