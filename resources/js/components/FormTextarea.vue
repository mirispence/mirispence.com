<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    defaultValue?: string | number;
    modelValue?: string | number;
    class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});
</script>

<template>
    <textarea
        v-model="modelValue"
        :class="
            cn(
                'flex min-h-[120px] w-full rounded-xl border border-border bg-white/50 px-4 py-3 text-base shadow-sm ring-offset-background transition-all placeholder:text-muted-foreground focus-visible:border-primary/50 focus-visible:bg-white focus-visible:ring-4 focus-visible:ring-primary/20 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                props.class,
            )
        "
    />
</template>
