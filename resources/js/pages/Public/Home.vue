<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
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
                            class="font-heading text-5xl font-black tracking-tight text-foreground sm:text-7xl"
                        >
                            Art & <span class="text-primary">Stories</span>
                        </h1>
                        <p
                            class="mt-8 max-w-xl text-xl leading-relaxed text-muted-foreground"
                        >
                            The portfolio and creative works of Miri Spence.
                            Exploring the intersections of visual art and
                            narrative through detailed illustrations and
                            compelling stories.
                        </p>
                        <div class="mt-10 flex flex-wrap gap-4">
                            <Link href="/art">
                                <Button
                                    size="lg"
                                    class="rounded-full bg-primary px-8"
                                    >View Gallery</Button
                                >
                            </Link>
                            <Link href="/contact?type=commission">
                                <Button
                                    variant="outline"
                                    size="lg"
                                    class="rounded-full px-8"
                                    >Commissions</Button
                                >
                            </Link>
                        </div>
                    </div>
                    <div class="relative mt-16 lg:mt-0">
                        <div
                            class="absolute -inset-4 rounded-full bg-gradient-to-tr from-primary/10 to-accent/10 blur-3xl"
                        />
                        <div class="relative grid grid-cols-2 gap-4">
                            <div v-if="featuredArtworks[0]" class="space-y-4">
                                <div
                                    class="aspect-[4/5] rotate-[-2deg] overflow-hidden rounded-3xl shadow-premium transition-transform hover:rotate-0"
                                >
                                    <img
                                        v-if="featuredArtworks[0].media_urls?.grid"
                                        :src="featuredArtworks[0].media_urls.grid.src"
                                        :srcset="featuredArtworks[0].media_urls.grid.srcset"
                                        :alt="featuredArtworks[0].alt_text || featuredArtworks[0].title"
                                        class="h-full w-full object-cover"
                                        sizes="(max-width: 1024px) 50vw, 400px"
                                    />
                                    <img
                                        v-else
                                        :src="featuredArtworks[0].image_url"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                            <div
                                v-if="featuredArtworks[1]"
                                class="space-y-4 pt-12"
                            >
                                <div
                                    class="aspect-[4/5] rotate-[2deg] overflow-hidden rounded-3xl shadow-premium transition-transform hover:rotate-0"
                                >
                                    <img
                                        v-if="featuredArtworks[1].media_urls?.grid"
                                        :src="featuredArtworks[1].media_urls.grid.src"
                                        :srcset="featuredArtworks[1].media_urls.grid.srcset"
                                        :alt="featuredArtworks[1].alt_text || featuredArtworks[1].title"
                                        class="h-full w-full object-cover"
                                        sizes="(max-width: 1024px) 50vw, 400px"
                                    />
                                    <img
                                        v-else
                                        :src="featuredArtworks[1].image_url"
                                        class="h-full w-full object-cover"
                                    />
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
                            class="font-heading text-4xl font-bold tracking-tight text-foreground"
                        >
                            Featured Art
                        </h2>
                        <div class="mt-2 h-1 w-20 rounded-full bg-primary/20" />
                    </div>
                    <Link
                        href="/art"
                        class="text-sm font-bold tracking-widest text-primary uppercase transition-colors hover:text-primary/80"
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
                        :is-revealed="false"
                    />
                </div>
            </div>

            <!-- Commission Callout -->
            <section
                class="relative mt-32 overflow-hidden rounded-[3rem] bg-panel p-12 text-center sm:p-20"
            >
                <div
                    class="absolute top-0 right-0 h-96 w-96 translate-x-1/2 -translate-y-1/2 rounded-full bg-accent/10 blur-3xl"
                />
                <div
                    class="absolute bottom-0 left-0 h-96 w-96 -translate-x-1/2 translate-y-1/2 rounded-full bg-primary/10 blur-3xl"
                />

                <div class="relative mx-auto max-w-2xl">
                    <h2 class="font-heading text-4xl font-bold text-foreground">
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
                            <Button
                                size="lg"
                                class="rounded-full bg-accent px-12 shadow-premium hover:bg-accent/90"
                            >
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
                            class="font-heading text-4xl font-bold tracking-tight text-foreground"
                        >
                            Featured Books
                        </h2>
                        <div class="mt-2 h-1 w-20 rounded-full bg-primary/20" />
                    </div>
                    <Link
                        href="/books"
                        class="text-sm font-bold tracking-widest text-primary uppercase transition-colors hover:text-primary/80"
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
                        class="group overflow-hidden border-none bg-white p-0"
                    >
                        <div class="aspect-[2/3] overflow-hidden">
                            <img
                                v-if="book.media_urls?.thumb"
                                :src="book.media_urls.thumb"
                                :alt="book.title"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                v-else
                                class="flex h-full items-center justify-center bg-panel font-heading text-muted-foreground italic"
                            >
                                No Cover
                            </div>
                        </div>
                        <div class="p-8">
                            <h3
                                class="font-heading text-xl font-bold text-foreground transition-colors group-hover:text-primary"
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
