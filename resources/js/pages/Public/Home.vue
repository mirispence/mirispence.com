<script setup lang="ts">
import {
    index as artworkIndex,
    show as artworkShow,
} from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { index as galleriesIndex } from '@/actions/App/Http/Controllers/Public/GalleryController';
import ArtCard from '@/components/ArtCard.vue';
import CommissionCta from '@/components/CommissionCta.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    /** Newest unflagged featured artwork, or null when none is eligible (ADR-0004). */
    heroArtwork: {
        id: number;
        title: string;
        slug: string;
        alt_text: string | null;
        media_urls: any;
    } | null;
    featuredArtworks: Array<any>;
    featuredBooks: Array<any>;
}>();

const heroImage = computed(
    () =>
        props.heroArtwork?.media_urls?.display ??
        props.heroArtwork?.media_urls?.grid ??
        null,
);
</script>

<template>
    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-16">
                    <div class="text-left">
                        <h1
                            class="font-display text-5xl tracking-tight text-foreground sm:text-7xl"
                        >
                            Art & <span class="text-primary">Stories</span>
                        </h1>
                        <p
                            class="mt-8 max-w-xl text-xl leading-relaxed text-muted-foreground"
                        >
                            The art and books of Miri Spence.<br />
                            Illustration, storytelling, and personal creative
                            work.
                        </p>
                        <div class="mt-10 flex flex-wrap gap-4">
                            <Link :href="galleriesIndex.url()">
                                <Button size="lg" class="bg-primary px-8"
                                    >View Galleries</Button
                                >
                            </Link>
                            <CommissionCta />
                        </div>
                    </div>
                    <div
                        v-if="heroImage"
                        class="mt-16 flex justify-center lg:mt-0 lg:justify-end"
                    >
                        <Link
                            :href="artworkShow.url(heroArtwork!.slug)"
                            class="block"
                        >
                            <img
                                fetchpriority="high"
                                :src="heroImage.src"
                                :srcset="heroImage.srcset"
                                :alt="
                                    heroArtwork!.alt_text || heroArtwork!.title
                                "
                                class="max-h-[70vh] w-auto max-w-full object-contain"
                                sizes="(max-width: 1024px) 100vw, 50vw"
                            />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <!-- Featured Art -->
            <div v-if="featuredArtworks.length > 0" class="mt-20">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            Featured Art
                        </h2>
                        <div class="mt-2 h-1 w-20 rounded-full bg-primary/20" />
                    </div>
                    <Link
                        :href="artworkIndex.url()"
                        class="text-sm font-semibold text-primary underline-offset-4 transition-colors hover:underline"
                    >
                        View all artwork →
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <ArtCard
                        v-for="artwork in featuredArtworks.slice(0, 6)"
                        :key="artwork.id"
                        :artwork="artwork"
                        :reload-only="['featuredArtworks']"
                    />
                </div>
            </div>

            <!-- Commission Callout -->
            <section
                class="relative mt-32 overflow-hidden rounded-lg border border-border bg-muted p-12 text-center sm:p-20"
            >
                <div class="relative mx-auto max-w-2xl">
                    <h2
                        class="font-display text-3xl text-foreground sm:text-4xl"
                    >
                        Have a vision in mind?
                    </h2>
                    <p
                        class="mt-6 text-lg leading-relaxed text-muted-foreground"
                    >
                        I am currently available for custom art commissions and
                        narrative collaborations. Let's bring your ideas to
                        life.
                    </p>
                    <div class="mt-10">
                        <CommissionCta />
                    </div>
                </div>
            </section>

            <div v-if="featuredBooks.length > 0" class="mt-32 mb-20">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2
                            class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
                        >
                            Featured Books
                        </h2>
                        <div class="mt-2 h-1 w-20 rounded-full bg-primary/20" />
                    </div>
                    <Link
                        href="/books"
                        class="text-sm font-semibold text-primary underline-offset-4 transition-colors hover:underline"
                    >
                        View All Books →
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <Card
                        v-for="book in featuredBooks"
                        :key="book.id"
                        class="group overflow-hidden border-none bg-card p-0"
                    >
                        <div class="aspect-[2/3] overflow-hidden">
                            <img
                                v-if="book.cover?.urls?.thumb"
                                :src="book.cover.urls.thumb"
                                :alt="book.title"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                v-else
                                class="flex h-full items-center justify-center bg-muted text-muted-foreground italic"
                            >
                                No Cover
                            </div>
                        </div>
                        <div class="p-8">
                            <h3
                                class="text-xl font-semibold text-foreground transition-colors group-hover:text-primary"
                            >
                                <Link :href="`/books/${book.slug}`">
                                    {{ book.title }}
                                </Link>
                            </h3>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
