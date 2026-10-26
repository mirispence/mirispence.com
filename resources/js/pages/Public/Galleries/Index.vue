<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { show as galleryShow } from '@/actions/App/Http/Controllers/Public/GalleryController';
import PublicPagination from '@/components/PublicPagination.vue';
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
                    class="font-display text-4xl tracking-tight wrap-break-word text-foreground sm:text-5xl"
                >
                    Galleries
                </h1>
                <p class="mt-4 text-xl text-muted-foreground">
                    Curated sets of themed illustrations and studies.
                </p>
                <Link
                    :href="artworkIndex.url()"
                    class="mt-6 inline-block text-sm font-semibold text-primary underline-offset-4 transition-colors hover:underline"
                >
                    View all artwork &rarr;
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="gallery in galleries.data"
                    :key="gallery.id"
                    class="group flex flex-col overflow-hidden border-none bg-card p-0"
                >
                    <div class="relative aspect-[4/5] overflow-hidden bg-muted">
                        <img
                            v-if="gallery.cover_urls?.grid?.src"
                            :src="gallery.cover_urls.grid.src"
                            :srcset="gallery.cover_urls.grid.srcset"
                            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                            :alt="gallery.name"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <!-- Placeholder -->
                        <div
                            v-else
                            class="flex h-full items-center justify-center text-2xl text-muted-foreground italic"
                        >
                            {{ gallery.name }}
                        </div>
                        <div
                            class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100 focus-within:opacity-100"
                        >
                            <Link
                                :href="galleryShow.url(gallery.slug)"
                                class="w-full"
                            >
                                <Button class="w-full">Explore Gallery</Button>
                            </Link>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-8">
                        <h3
                            class="mb-3 text-2xl font-semibold text-foreground transition-colors group-hover:text-primary"
                        >
                            <Link :href="galleryShow.url(gallery.slug)">
                                {{ gallery.name }}
                            </Link>
                        </h3>
                        <div
                            class="prose dark:prose-invert prose-sm line-clamp-2 leading-relaxed text-muted-foreground"
                            v-if="gallery.description_html"
                            v-html="gallery.description_html"
                        ></div>
                    </div>
                </Card>
            </div>

            <PublicPagination :links="galleries.meta.links" />
        </div>
    </PublicLayout>
</template>
