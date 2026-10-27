<script setup lang="ts">
import { index as galleriesIndex } from '@/actions/App/Http/Controllers/Public/GalleryController';
import ArtCard from '@/components/ArtCard.vue';
import ArtLightbox from '@/components/ArtLightbox.vue';
import PublicPagination from '@/components/PublicPagination.vue';
import { Button } from '@/components/ui/button';
import { copy } from '@/content/copy';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { PublicArtwork } from '@/types/publicArtwork';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    gallery: any;
    artworks: {
        data: PublicArtwork[];
        links: Array<any>;
        meta: any;
    };
}>();

const lightboxIndex = ref<number | null>(null);
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div
                class="mb-16 flex flex-col border-b border-border pb-12 md:flex-row md:items-end md:justify-between"
            >
                <div class="max-w-3xl">
                    <h1
                        class="font-display text-4xl tracking-tight wrap-break-word text-foreground sm:text-5xl"
                    >
                        {{ gallery.name }}
                    </h1>
                    <div
                        class="prose dark:prose-invert mt-4 text-xl text-muted-foreground"
                        v-if="gallery.description_html"
                        v-html="gallery.description_html"
                    ></div>
                </div>

                <div class="mt-8 md:mt-0">
                    <Link :href="galleriesIndex.url()">
                        <Button
                            variant="ghost"
                            class="text-muted-foreground hover:text-primary"
                        >
                            <svg
                                class="mr-2 h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Back to Galleries
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <ArtCard
                    v-for="(artwork, index) in artworks.data"
                    :key="artwork.id"
                    :artwork="artwork"
                    :reload-only="['artworks']"
                    @click-image="lightboxIndex = index"
                />
            </div>

            <div
                v-if="artworks.data.length === 0"
                class="rounded-lg border border-border bg-card py-24 text-center"
            >
                <div
                    class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-muted"
                >
                    <svg
                        aria-hidden="true"
                        class="h-8 w-8 text-muted-foreground"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-muted-foreground">
                    {{ copy.gallery.emptyHeading }}
                </h3>
                <p class="mt-2 text-muted-foreground">
                    {{ copy.gallery.emptyBody }}
                </p>
            </div>

            <PublicPagination :links="artworks.meta.links" />
        </div>

        <ArtLightbox
            v-model:index="lightboxIndex"
            :artworks="artworks.data"
            :reload-only="['artworks']"
        />
    </PublicLayout>
</template>
