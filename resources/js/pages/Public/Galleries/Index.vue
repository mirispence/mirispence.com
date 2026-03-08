<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    galleries: {
        data: Array<any>;
        links: Array<any>;
        meta: any;
    };
}>();
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h1
                    class="font-heading text-5xl font-black tracking-tight text-foreground"
                >
                    Collections
                </h1>
                <p class="mt-4 text-xl text-muted-foreground">
                    Curated sets of themed illustrations and studies.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="gallery in galleries.data"
                    :key="gallery.id"
                    class="group flex flex-col overflow-hidden border-none bg-white p-0"
                >
                    <div class="relative aspect-[4/5] overflow-hidden bg-panel">
                        <img
                            v-if="gallery.media_urls?.thumb"
                            :src="gallery.media_urls.thumb"
                            :alt="gallery.name"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <!-- Placeholder -->
                        <div
                            v-else
                            class="flex h-full items-center justify-center font-heading text-2xl text-muted-foreground italic"
                        >
                            {{ gallery.name }}
                        </div>
                        <div
                            class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        >
                            <Link
                                :href="`/galleries/${gallery.slug}`"
                                class="w-full"
                            >
                                <Button
                                    variant="accent"
                                    class="w-full rounded-full"
                                    >Explore Collection</Button
                                >
                            </Link>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-8">
                        <h3
                            class="mb-3 font-heading text-2xl font-bold text-foreground transition-colors group-hover:text-primary"
                        >
                            <Link :href="`/galleries/${gallery.slug}`">
                                {{ gallery.name }}
                            </Link>
                        </h3>
                        <div
                            class="line-clamp-2 leading-relaxed text-muted-foreground prose dark:prose-invert prose-sm"
                            v-if="gallery.description_html"
                            v-html="gallery.description_html"
                        ></div>
                    </div>
                </Card>
            </div>

            <!-- Pagination -->
            <div
                class="mt-20 flex items-center justify-center"
                v-if="galleries.meta && galleries.meta.links && galleries.meta.links.length > 3"
            >
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <template v-for="link in galleries.meta.links" :key="link.label">
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
            <!-- Fallback for older structure if meta is missing but links exist directly -->
            <div
                class="mt-20 flex items-center justify-center"
                v-else-if="galleries.links && galleries.links.length > 3"
            >
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <template v-for="link in galleries.links" :key="link.label">
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
        </div>
    </PublicLayout>
</template>
