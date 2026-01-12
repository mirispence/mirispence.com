<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}>();
</script>

<template>
    <div v-if="links.length > 3" class="mt-8 flex items-center justify-center">
        <nav class="flex items-center gap-1" aria-label="Pagination">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-medium text-gray-400"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :href="link.url"
                    class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-bold transition-all"
                    :class="
                        link.active
                            ? 'bg-indigo-600 text-white shadow-md'
                            : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                    "
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
