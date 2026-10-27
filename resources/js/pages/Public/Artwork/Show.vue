<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { show as galleryShow } from '@/actions/App/Http/Controllers/Public/GalleryController';
import ArtLightbox from '@/components/ArtLightbox.vue';
import CommissionCta from '@/components/CommissionCta.vue';
import NsfwGate from '@/components/NsfwGate.vue';
import { Button } from '@/components/ui/button';
import { copy } from '@/content/copy';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    artwork: any;
}>();

/** 0 while the lightbox shows this artwork, null when closed. */
const lightboxIndex = ref<number | null>(null);

/** Published galleries only (the controller filters them). */
const galleries = computed<{ id: number; name: string; slug: string }[]>(
    () => props.artwork.galleries ?? [],
);
</script>

<template>
    <PublicLayout>
        <article class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <!-- Image: content width at its natural aspect ratio, capped at 85vh tall -->
            <NsfwGate
                :withheld="artwork.media_withheld"
                :artwork-id="artwork.id"
                :reload-only="['artwork']"
                variant="detail"
                v-slot="{ revealed }"
            >
                <div
                    v-if="artwork.media_urls?.display"
                    class="relative mx-auto w-fit max-w-full"
                >
                    <!-- With a w-descriptor srcset the intrinsic width is the `sizes`
                         slot, so wide pieces fill the content width and tall ones
                         shrink to the height cap without a letterboxing box. -->
                    <img
                        :src="artwork.media_urls.display.src"
                        :srcset="artwork.media_urls.display.srcset"
                        :alt="artwork.alt_text || artwork.title"
                        class="block h-auto max-h-[85vh] w-auto max-w-full"
                        sizes="(min-width: 1280px) 1216px, calc(100vw - 2rem)"
                        loading="eager"
                        fetchpriority="high"
                    />

                    <!-- Covers the image to discourage casual saving; opens the lightbox -->
                    <button
                        v-if="revealed"
                        type="button"
                        class="absolute inset-0 z-10 cursor-zoom-in focus-visible:-outline-offset-4"
                        :aria-label="`Enlarge ${artwork.title}`"
                        aria-haspopup="dialog"
                        @click="
                            ($event.currentTarget as HTMLElement).focus();
                            lightboxIndex = 0;
                        "
                    ></button>
                </div>

                <!-- Withheld: no dimensions are known, so reserve a sensible minimum for the gate -->
                <div
                    v-else-if="artwork.media_withheld"
                    class="h-[70vh] min-h-96 w-full bg-card"
                ></div>

                <div
                    v-else
                    class="flex h-80 items-center justify-center bg-card text-2xl text-muted-foreground italic"
                >
                    No image available
                </div>
            </NsfwGate>

            <!-- Text: one readable column -->
            <div class="mx-auto mt-12 max-w-[65ch]">
                <h1
                    class="font-display text-4xl leading-tight tracking-tight wrap-break-word text-foreground sm:text-5xl"
                >
                    {{ artwork.title }}
                </h1>

                <p
                    v-if="artwork.created_on || galleries.length"
                    class="mt-4 text-sm text-muted-foreground"
                >
                    <span v-if="artwork.created_on">{{
                        artwork.created_on
                    }}</span>
                    <span
                        v-if="artwork.created_on && galleries.length"
                        aria-hidden="true"
                        class="mx-2"
                        >·</span
                    >
                    <span v-if="galleries.length"
                        >In
                        <template
                            v-for="(gallery, i) in galleries"
                            :key="gallery.id"
                            ><Link
                                :href="galleryShow.url(gallery.slug)"
                                class="font-semibold text-primary underline-offset-4 hover:underline"
                                >{{ gallery.name }}</Link
                            ><template v-if="i < galleries.length - 1"
                                >,
                            </template></template
                        ></span
                    >
                </p>

                <div
                    v-if="artwork.description_html"
                    class="artwork-prose prose prose-lg prose-slate mt-8 max-w-none leading-relaxed text-muted-foreground"
                    v-html="artwork.description_html"
                />

                <!-- Tags -->
                <ul
                    v-if="artwork.tags && artwork.tags.length"
                    class="mt-8 flex flex-wrap gap-3"
                    aria-label="Tags"
                >
                    <li v-for="tag in artwork.tags" :key="tag.id">
                        <Button
                            as-child
                            variant="outline"
                            size="sm"
                            class="h-10 rounded-full border-input bg-card transition-all hover:border-primary hover:text-primary"
                        >
                            <Link
                                :href="
                                    artworkIndex.url({
                                        query: { tag: tag.slug },
                                    })
                                "
                            >
                                {{ tag.name }}
                            </Link>
                        </Button>
                    </li>
                </ul>

                <!-- CTA -->
                <div class="mt-12 border-t border-border pt-8">
                    <CommissionCta />
                    <p class="mt-4 text-sm text-muted-foreground italic">
                        {{ copy.artwork.commissionBlurb }}
                    </p>
                </div>

                <div class="mt-12">
                    <Button
                        as-child
                        variant="ghost"
                        class="text-muted-foreground hover:text-primary"
                    >
                        <Link :href="artworkIndex.url()">
                            <svg
                                class="mr-2 h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Back to Artwork
                        </Link>
                    </Button>
                </div>
            </div>
        </article>

        <ArtLightbox
            v-model:index="lightboxIndex"
            :artworks="[artwork]"
            :reload-only="['artwork']"
            :navigation="false"
            :details-link="false"
        />
    </PublicLayout>
</template>
