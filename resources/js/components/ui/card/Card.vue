<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="card"
    :class="
      cn(
        'bg-white text-card-foreground flex flex-col gap-6 rounded-2xl border-none shadow-premium transition-all duration-300 hover:shadow-2xl hover:-translate-y-1',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
