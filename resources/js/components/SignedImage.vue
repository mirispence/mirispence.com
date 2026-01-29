<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    urls: {
        src: string;
        srcset: string;
    };
    alt: string;
    sizes?: string;
    className?: string;
}>();

const defaultSizes = "(max-width: 768px) 100vw, 1200px";
</script>

<template>
    <div class="relative inline-block overflow-hidden">
        <img
            :src="urls.src"
            :srcset="urls.srcset"
            :sizes="sizes || defaultSizes"
            :alt="alt"
            :class="className"
            loading="lazy"
            draggable="false"
            oncontextmenu="return true;"
            class="transition-all duration-700"
        />
        <!-- Transparent overlay to deter direct right-click "Open image in new tab" -->
        <div class="absolute inset-0 z-10 opacity-0 cursor-default" aria-hidden="true"></div>
    </div>
</template>

<style scoped>
/* Ensure the overlay doesn't block the context menu if requested, 
   but it specifically sits above the img to intercept primary actions 
   depending on how the user interacts. 
   Per requirements: "implement transparent overlay UI only. Do not disable context menu."
*/
</style>
