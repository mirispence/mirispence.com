<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    artwork: any;
}>();

const isRevealed = ref(false);

const revealNSFW = () => {
    isRevealed.value = true;
};
</script>

<template>
    <Head :title="artwork.title" />

    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-16">
                <!-- Image Container -->
                <div class="flex justify-center lg:sticky lg:top-32">
                    <Card
                        class="group relative w-full overflow-hidden rounded-[2.5rem] border-none p-0 shadow-premium"
                        :class="{
                            'cursor-pointer': artwork.nsfw_flag && !isRevealed,
                        }"
                        @click="
                            artwork.nsfw_flag && !isRevealed
                                ? revealNSFW()
                                : null
                        "
                    >
                        <div class="aspect-square bg-white">
                            <img
                                v-if="artwork.image_url"
                                :src="artwork.image_url"
                                :alt="artwork.alt_text || artwork.title"
                                class="h-full w-full object-contain transition-all duration-700"
                                :class="{
                                    'scale-110 opacity-40 blur-3xl grayscale':
                                        artwork.nsfw_flag && !isRevealed,
                                }"
                            />
                            <!-- Placeholder -->
                            <div
                                v-else
                                class="flex h-full items-center justify-center font-heading text-2xl text-muted-foreground italic"
                            >
                                No Image available
                            </div>

                            <!-- NSFW Overlay -->
                            <div
                                v-if="artwork.nsfw_flag && !isRevealed"
                                class="absolute inset-0 flex flex-col items-center justify-center bg-panel/20 backdrop-blur-md"
                            >
                                <div
                                    class="max-w-[280px] rounded-[2rem] bg-white/80 p-8 text-center shadow-2xl"
                                >
                                    <div
                                        class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-accent/10 text-accent"
                                    >
                                        <svg
                                            class="h-8 w-8"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="mb-2 font-heading text-xl font-bold text-foreground"
                                    >
                                        Age Restricted
                                    </h3>
                                    <p
                                        class="mb-6 text-sm text-muted-foreground"
                                    >
                                        This artwork contains NSFW content.
                                    </p>
                                    <Button
                                        variant="accent"
                                        size="lg"
                                        class="w-full rounded-full font-bold"
                                        >Show Artwork</Button
                                    >
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Info -->
                <div class="mt-16 px-4 sm:px-0 lg:mt-0">
                    <div class="mb-8 flex flex-col gap-4">
                        <h1
                            class="font-heading text-5xl leading-tight font-black tracking-tight text-foreground"
                        >
                            {{ artwork.title }}
                        </h1>
                        <div
                            v-if="artwork.created_on"
                            class="flex items-center gap-2"
                        >
                            <span
                                class="text-xs font-black tracking-[0.2em] text-accent uppercase"
                                >Created</span
                            >
                            <span
                                class="text-sm font-bold text-muted-foreground"
                                >{{ artwork.created_on }}</span
                            >
                        </div>
                    </div>

                    <div
                        class="prose prose-lg prose-slate mb-12 max-w-none leading-relaxed text-muted-foreground"
                    >
                        <div v-html="artwork.description" />
                    </div>

                    <div class="space-y-12">
                        <!-- Tags -->
                        <div v-if="artwork.tags && artwork.tags.length">
                            <h2
                                class="mb-4 text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                Tags
                            </h2>
                            <div class="flex flex-wrap gap-3">
                                <Link
                                    v-for="tag in artwork.tags"
                                    :key="tag.id"
                                    :href="`/art?tag=${tag.slug}`"
                                >
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="h-10 rounded-full border-border/60 bg-white/50 transition-all hover:border-primary hover:text-primary"
                                    >
                                        {{ tag.name }}
                                    </Button>
                                </Link>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="border-t border-border/60 pt-8">
                            <Link href="/contact?type=commission" class="block">
                                <Button
                                    size="lg"
                                    class="group h-16 w-full rounded-full bg-primary text-xl font-bold shadow-premium"
                                >
                                    Request a Commission
                                    <svg
                                        class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        />
                                    </svg>
                                </Button>
                            </Link>
                            <p
                                class="mt-4 text-center text-sm font-medium text-muted-foreground italic"
                            >
                                Loved this style? Send a request to work
                                together!
                            </p>
                        </div>
                    </div>

                    <div class="mt-16 border-t border-border/60 pt-12">
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
            </div>
        </div>
    </PublicLayout>
</template>
