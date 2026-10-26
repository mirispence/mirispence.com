<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { show as galleryShow } from '@/actions/App/Http/Controllers/Public/GalleryController';
import ArtLightbox from '@/components/ArtLightbox.vue';
import CommissionCta from '@/components/CommissionCta.vue';
import NsfwGate from '@/components/NsfwGate.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    artwork: any;
}>();

/** 0 while the lightbox shows this artwork, null when closed. */
const lightboxIndex = ref<number | null>(null);
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-16">
                <!-- Image Container -->
                <div class="flex justify-center lg:sticky lg:top-32">
                    <Card
                        class="group relative w-full overflow-hidden rounded-sm border-none p-0 shadow-none hover:translate-y-0 hover:shadow-none"
                    >
                        <div class="aspect-square bg-card">
                            <NsfwGate
                                :withheld="artwork.media_withheld"
                                :artwork-id="artwork.id"
                                :reload-only="['artwork']"
                                variant="detail"
                                v-slot="{ revealed }"
                            >
                                <img
                                    v-if="artwork.media_urls?.display"
                                    :src="artwork.media_urls.display.src"
                                    :srcset="artwork.media_urls.display.srcset"
                                    :alt="artwork.alt_text || artwork.title"
                                    class="h-full w-full object-contain transition-all duration-700"
                                    sizes="(max-width: 1024px) 100vw, 800px"
                                    loading="eager"
                                    fetchpriority="high"
                                />
                                <!-- Placeholder -->
                                <div
                                    v-else-if="!artwork.media_withheld"
                                    class="flex h-full items-center justify-center text-2xl text-muted-foreground italic"
                                >
                                    No Image available
                                </div>

                                <!-- Covers the image to discourage casual saving; opens the lightbox -->
                                <button
                                    v-if="
                                        revealed && artwork.media_urls?.display
                                    "
                                    type="button"
                                    class="absolute inset-0 z-10 cursor-zoom-in focus-visible:-outline-offset-4"
                                    :aria-label="`Enlarge ${artwork.title}`"
                                    aria-haspopup="dialog"
                                    @click="
                                        (
                                            $event.currentTarget as HTMLElement
                                        ).focus();
                                        lightboxIndex = 0;
                                    "
                                ></button>
                            </NsfwGate>
                        </div>
                    </Card>
                </div>

                <!-- Info -->
                <div class="mt-16 px-4 sm:px-0 lg:mt-0">
                    <div class="mb-8 flex flex-col gap-4">
                        <h1
                            class="font-display text-4xl leading-tight tracking-tight wrap-break-word text-foreground sm:text-5xl"
                        >
                            {{ artwork.title }}
                        </h1>
                        <div
                            v-if="artwork.created_on"
                            class="flex items-center gap-2"
                        >
                            <span class="text-sm font-semibold text-primary"
                                >Created</span
                            >
                            <span
                                class="text-sm font-semibold text-muted-foreground"
                                >{{ artwork.created_on }}</span
                            >
                        </div>
                    </div>

                    <div
                        class="prose prose-lg prose-slate mb-12 max-w-none leading-relaxed text-muted-foreground"
                    >
                        <div
                            class="artwork-prose"
                            v-html="artwork.description_html"
                        />
                    </div>

                    <div class="space-y-12">
                        <!-- Tags -->
                        <div v-if="artwork.tags && artwork.tags.length">
                            <h2
                                class="mb-4 text-sm font-semibold text-muted-foreground"
                            >
                                Tags
                            </h2>
                            <div class="flex flex-wrap gap-3">
                                <Link
                                    v-for="tag in artwork.tags"
                                    :key="tag.id"
                                    :href="
                                        artworkIndex.url({
                                            query: { tag: tag.slug },
                                        })
                                    "
                                >
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="h-10 rounded-full border-input bg-card transition-all hover:border-primary hover:text-primary"
                                    >
                                        {{ tag.name }}
                                    </Button>
                                </Link>
                            </div>
                        </div>

                        <!-- Galleries this artwork belongs to (published only) -->
                        <div
                            v-if="artwork.galleries && artwork.galleries.length"
                        >
                            <h2
                                class="mb-4 text-sm font-semibold text-muted-foreground"
                            >
                                In Galleries
                            </h2>
                            <ul class="flex flex-wrap gap-x-6 gap-y-2">
                                <li
                                    v-for="gallery in artwork.galleries"
                                    :key="gallery.id"
                                >
                                    <Link
                                        :href="galleryShow.url(gallery.slug)"
                                        class="font-semibold text-primary underline-offset-4 hover:underline"
                                    >
                                        {{ gallery.name }}
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- CTA -->
                        <div class="border-t border-border pt-8">
                            <CommissionCta class="w-full" />
                            <p
                                class="mt-4 text-center text-sm font-medium text-muted-foreground italic"
                            >
                                Loved this style? Send a request to work
                                together!
                            </p>
                        </div>
                    </div>

                    <div class="mt-16 border-t border-border pt-12">
                        <Link :href="artworkIndex.url()">
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
                                Back to Artwork
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <ArtLightbox
            v-model:index="lightboxIndex"
            :artworks="[artwork]"
            :reload-only="['artwork']"
            :navigation="false"
            :details-link="false"
        />
    </PublicLayout>
</template>
