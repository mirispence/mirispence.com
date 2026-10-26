<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { show as galleryShow } from '@/actions/App/Http/Controllers/Public/GalleryController';
import PublicPagination from '@/components/PublicPagination.vue';
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
                <Link
                    v-for="gallery in galleries.data"
                    :key="gallery.id"
                    :href="galleryShow.url(gallery.slug)"
                    :aria-labelledby="`gallery-${gallery.id}-name`"
                    :aria-describedby="`gallery-${gallery.id}-count`"
                    class="group block"
                >
                    <div
                        class="relative aspect-[4/5] overflow-hidden rounded-sm bg-muted"
                    >
                        <img
                            v-if="gallery.cover_urls?.grid?.src"
                            :src="gallery.cover_urls.grid.src"
                            :srcset="gallery.cover_urls.grid.srcset"
                            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                            alt=""
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <h2
                        :id="`gallery-${gallery.id}-name`"
                        class="mt-3 text-xl font-semibold text-foreground underline-offset-4 group-hover:text-primary group-hover:underline"
                    >
                        {{ gallery.name }}
                    </h2>
                    <p
                        :id="`gallery-${gallery.id}-count`"
                        class="mt-0.5 text-sm text-muted-foreground"
                    >
                        {{ gallery.published_artworks_count }}
                        {{
                            gallery.published_artworks_count === 1
                                ? 'artwork'
                                : 'artworks'
                        }}
                    </p>
                </Link>
            </div>

            <PublicPagination :links="galleries.meta.links" />
        </div>
    </PublicLayout>
</template>
