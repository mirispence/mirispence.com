<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    gallery: any;
}>();
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div
                class="mb-16 flex flex-col border-b border-border pb-12 md:flex-row md:items-end md:justify-between"
            >
                <div class="max-w-3xl">
                    <h1
                        class="font-heading text-5xl font-black tracking-tight text-foreground"
                    >
                        {{ gallery.name }}
                    </h1>
                    <div
                        class="mt-4 text-xl text-muted-foreground prose dark:prose-invert"
                        v-if="gallery.description_html"
                        v-html="gallery.description_html"
                    ></div>
                </div>

                <div class="mt-8 md:mt-0">
                    <Link href="/art">
                        <Button
                            variant="ghost"
                            class="rounded-full text-muted-foreground hover:text-primary"
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
                            Back to Gallery
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <ArtCard
                    v-for="artwork in gallery.artworks"
                    :key="artwork.id"
                    :artwork="artwork"
                />
            </div>

            <div
                v-if="gallery.artworks.length === 0"
                class="rounded-[3rem] border border-white/60 bg-white/40 py-24 text-center"
            >
                <div
                    class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-panel"
                >
                    <svg
                        class="h-8 w-8 text-muted-foreground/40"
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
                <h3 class="font-heading text-2xl font-bold text-foreground/60">
                    No artworks yet
                </h3>
                <p class="mt-2 text-muted-foreground/60">
                    Check back soon for new additions to this collection.
                </p>
            </div>
        </div>
    </PublicLayout>
</template>
