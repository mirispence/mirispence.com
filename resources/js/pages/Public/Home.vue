<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    heroArtworks: Array<any>;
    featuredArtworks: Array<any>;
    featuredBooks: Array<any>;
}>();
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
                            <Link href="/art">
                                <Button size="lg" class="bg-primary px-8"
                                    >View Gallery</Button
                                >
                            </Link>
                            <Link href="/contact?type=commission">
                                <Button variant="outline" size="lg" class="px-8"
                                    >Commissions</Button
                                >
                            </Link>
                        </div>
                    </div>
                    <div class="relative mt-16 lg:mt-0">
                        <div class="relative grid grid-cols-2 gap-4">
                            <div v-if="heroArtworks[0]" class="space-y-4">
                                <div
                                    class="aspect-[4/5] rotate-[-2deg] overflow-hidden rounded-sm transition-transform hover:rotate-0"
                                >
                                    <img
                                        fetchpriority="high"
                                        v-if="heroArtworks[0].media_urls?.grid"
                                        :src="
                                            heroArtworks[0].media_urls.grid.src
                                        "
                                        :srcset="
                                            heroArtworks[0].media_urls.grid
                                                .srcset
                                        "
                                        :alt="
                                            heroArtworks[0].alt_text ||
                                            heroArtworks[0].title
                                        "
                                        class="h-full w-full object-cover"
                                        sizes="(max-width: 1024px) 50vw, 400px"
                                    />
                                    <!-- Invisible overlay to discourage casual image saving -->
                                    <div
                                        class="absolute inset-0 z-[5] cursor-pointer"
                                        @click="$emit('click-image')"
                                    ></div>
                                </div>
                            </div>
                            <div v-if="heroArtworks[1]" class="space-y-4 pt-12">
                                <div
                                    class="aspect-[4/5] rotate-[2deg] overflow-hidden rounded-sm transition-transform hover:rotate-0"
                                >
                                    <img
                                        fetchpriority="high"
                                        v-if="heroArtworks[1].media_urls?.grid"
                                        :src="
                                            heroArtworks[1].media_urls.grid.src
                                        "
                                        :srcset="
                                            heroArtworks[1].media_urls.grid
                                                .srcset
                                        "
                                        :alt="
                                            heroArtworks[1].alt_text ||
                                            heroArtworks[1].title
                                        "
                                        class="h-full w-full object-cover"
                                        sizes="(max-width: 1024px) 50vw, 400px"
                                    />
                                    <!-- Invisible overlay to discourage casual image saving -->
                                    <div
                                        class="absolute inset-0 z-[5] cursor-pointer"
                                        @click="$emit('click-image')"
                                    ></div>
                                </div>
                            </div>
                        </div>
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
                        href="/art"
                        class="text-sm font-semibold text-primary underline-offset-4 transition-colors hover:underline"
                    >
                        Explore Gallery →
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
                        <Link href="/contact?type=commission">
                            <Button size="lg" variant="accent" class="px-12">
                                Start a Commission
                            </Button>
                        </Link>
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
