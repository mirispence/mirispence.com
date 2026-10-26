<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { index as galleriesIndex } from '@/actions/App/Http/Controllers/Public/GalleryController';
import ArtCard from '@/components/ArtCard.vue';
import ArtLightbox from '@/components/ArtLightbox.vue';
import PublicPagination from '@/components/PublicPagination.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    artworks: {
        data: Array<any>;
        links: Array<any>;
        meta: any;
    };
    filters: {
        tag?: string;
    };
    /** The tag named by `?tag=`, or null when there is none or it is unknown. */
    activeTag: { name: string; slug: string } | null;
}>();

// ArtCard only opens the lightbox for a revealed artwork, but prev/next can land
// on one the server still withholds; ArtLightbox shows the gate for those.
const lightboxIndex = ref<number | null>(null);
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div
                class="flex flex-col gap-8 border-b border-border pb-12 md:flex-row md:items-end md:justify-between"
            >
                <div>
                    <h1
                        class="font-display text-4xl tracking-tight wrap-break-word text-foreground sm:text-5xl"
                    >
                        Artwork
                    </h1>
                    <p class="mt-4 text-lg text-muted-foreground">
                        All of my recent illustrations and character art.
                    </p>
                    <p
                        v-if="filters.tag"
                        class="mt-4 flex flex-wrap items-center gap-3 text-sm text-muted-foreground"
                    >
                        <span>
                            Tagged
                            <span class="font-semibold text-foreground">{{
                                activeTag?.name ?? filters.tag
                            }}</span>
                        </span>
                        <Link
                            :href="artworkIndex.url()"
                            class="font-semibold text-primary underline-offset-4 hover:underline"
                        >
                            Clear filter
                        </Link>
                    </p>
                </div>

                <Link
                    :href="galleriesIndex.url()"
                    class="text-sm font-semibold text-primary underline-offset-4 transition-colors hover:underline"
                >
                    Browse Galleries &rarr;
                </Link>
            </div>

            <div
                class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
            >
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
                class="mt-16 rounded-lg border border-border bg-card py-24 text-center"
            >
                <h2 class="text-2xl font-semibold text-muted-foreground">
                    No artwork found
                </h2>
                <p class="mt-2 text-muted-foreground">
                    <template v-if="filters.tag">
                        Nothing is tagged with that yet.
                        <Link
                            :href="artworkIndex.url()"
                            class="font-semibold text-primary underline-offset-4 hover:underline"
                        >
                            See all artwork
                        </Link>
                    </template>
                    <template v-else>Check back soon for new work.</template>
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
