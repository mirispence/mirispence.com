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
    <div v-if="links.length > 3" class="mt-20 flex items-center justify-center">
        <nav class="flex items-center gap-2" aria-label="Pagination">
            <template v-for="link in links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-bold transition-all"
                    :class="
                        link.active
                            ? 'bg-primary text-white shadow-lg'
                            : 'bg-white text-muted-foreground hover:bg-panel hover:text-primary'
                    "
                >
                    <span v-html="link.label"></span>
                </Link>
                <span
                    v-else
                    class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-medium text-muted-foreground/40"
                    v-html="link.label"
                ></span>
            </template>
        </nav>
    </div>
</template>
