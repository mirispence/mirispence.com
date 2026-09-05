<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import NsfwGate from '@/components/NsfwGate.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

defineProps<{
    artwork: any;
}>();

const showLightbox = ref(false);
const isZoomed = ref(false);

const openLightbox = () => {
    showLightbox.value = true;
};

const closeLightbox = () => {
    showLightbox.value = false;
    isZoomed.value = false;
};

const toggleZoom = () => {
    isZoomed.value = !isZoomed.value;
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && showLightbox.value) {
        closeLightbox();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-16">
                <!-- Image Container -->
                <div class="flex justify-center lg:sticky lg:top-32">
                    <Card
                        class="group relative w-full overflow-hidden rounded-[2.5rem] border-none p-0 shadow-premium"
                    >
                        <div class="aspect-square bg-white">
                            <NsfwGate :nsfw="artwork.nsfw_flag" variant="detail" v-slot="{ revealed }">
                                <img
                                    v-if="artwork.media_urls?.display"
                                    :src="artwork.media_urls.display.src"
                                    :srcset="artwork.media_urls.display.srcset"
                                    :alt="artwork.alt_text || artwork.title"
                                    class="h-full w-full object-contain transition-all duration-700"
                                    :class="{ 'scale-110 opacity-40 blur-3xl grayscale': !revealed }"
                                    sizes="(max-width: 1024px) 100vw, 800px"
                                    loading="eager"
                                    fetchpriority="high"
                                />
                                <!-- Placeholder -->
                                <div
                                    v-else
                                    class="flex h-full items-center justify-center font-heading text-2xl text-muted-foreground italic"
                                >
                                    No Image available
                                </div>

                                <!-- Invisible overlay to discourage casual image saving -->
                                <div
                                    v-if="revealed && artwork.media_urls?.display"
                                    class="absolute inset-0 z-10 cursor-pointer"
                                    @click="openLightbox"
                                ></div>
                            </NsfwGate>
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
                        <div class="artwork-prose" v-html="artwork.description_html" />
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

        <!-- Lightbox -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showLightbox"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/95 overflow-hidden"
                @click.self="closeLightbox"
            >
                <button
                    class="absolute top-8 right-8 z-[110] text-white/70 hover:text-white transition-colors"
                    @click="closeLightbox"
                >
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div
                    class="relative h-full w-full flex-1 overflow-auto flex items-center justify-center p-4 sm:p-8"
                    @click.self="closeLightbox"
                >
                    <div
                        class="relative inline-block transition-all duration-300 m-auto"
                    >
                        <img
                            v-if="artwork.media_urls?.display"
                            :src="artwork.media_urls.display.src"
                            :srcset="artwork.media_urls.display.srcset"
                            :alt="artwork.alt_text || artwork.title"
                            :class="[
                                'shadow-2xl transition-all duration-500 rounded-lg',
                                isZoomed ? 'max-w-none' : 'max-h-[85vh] max-w-full object-contain'
                            ]"
                            sizes="100vw"
                        />
                        <!-- Invisible overlay to discourage casual image saving -->
                        <div
                            class="absolute inset-0 z-10"
                            :class="isZoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                            @click="toggleZoom"
                        ></div>
                    </div>
                </div>

                <div class="relative z-20 pb-10 pt-4 text-center bg-black/40 backdrop-blur-sm w-full">
                    <h2 class="font-heading text-2xl font-bold text-white">{{ artwork.title }}</h2>
                </div>
            </div>
        </Transition>
    </PublicLayout>
</template>
