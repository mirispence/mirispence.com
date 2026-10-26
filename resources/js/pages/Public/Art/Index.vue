<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import FormSelect from '@/components/FormSelect.vue';
import NsfwGate from '@/components/NsfwGate.vue';
import PublicPagination from '@/components/PublicPagination.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps<{
    artworks: {
        data: Array<any>;
        links: Array<any>;
        meta: any;
    };
    filters: {
        tag?: string;
        gallery?: string;
    };
    galleries: Array<any>;
}>();

const selectedGallery = ref(props.filters.gallery || '');

const activeLightboxIndex = ref<number | null>(null);
const isZoomed = ref(false);
const activeArtwork = computed(() =>
    activeLightboxIndex.value !== null
        ? props.artworks.data[activeLightboxIndex.value]
        : null,
);

// ArtCard only emits click-image for a revealed artwork, but prev/next can land on
// one the server still withholds (media_withheld), so the lightbox renders the gate
// in place for those. Revealing reloads `artworks`; preserveState keeps the index.
const openLightbox = (index: number) => {
    activeLightboxIndex.value = index;
};

const closeLightbox = () => {
    activeLightboxIndex.value = null;
    isZoomed.value = false;
};

const toggleZoom = () => {
    isZoomed.value = !isZoomed.value;
};

const nextImage = () => {
    if (activeLightboxIndex.value === null) return;
    isZoomed.value = false;
    if (activeLightboxIndex.value < props.artworks.data.length - 1) {
        activeLightboxIndex.value++;
    } else {
        activeLightboxIndex.value = 0; // Loop to start
    }
};

const prevImage = () => {
    if (activeLightboxIndex.value === null) return;
    isZoomed.value = false;
    if (activeLightboxIndex.value > 0) {
        activeLightboxIndex.value--;
    } else {
        activeLightboxIndex.value = props.artworks.data.length - 1; // Loop to end
    }
};

const handleKeydown = (e: KeyboardEvent) => {
    if (activeLightboxIndex.value === null) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

watch(selectedGallery, (value) => {
    router.get(
        '/art',
        { gallery: value },
        { preserveState: true, replace: true },
    );
});
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
                        Gallery
                    </h1>
                    <p class="mt-4 text-lg text-muted-foreground">
                        A collection of my recent illustrations and character
                        art.
                    </p>
                </div>

                <div class="w-full md:w-64">
                    <label
                        class="mb-2 block text-sm font-semibold text-muted-foreground"
                        >Filter by Gallery</label
                    >
                    <FormSelect v-model="selectedGallery">
                        <option value="">All Collections</option>
                        <option
                            v-for="gallery in galleries"
                            :key="gallery.id"
                            :value="gallery.slug"
                        >
                            {{ gallery.name }}
                        </option>
                    </FormSelect>
                </div>
            </div>

            <div
                class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
            >
                <ArtCard
                    v-for="(artwork, index) in artworks.data"
                    :key="artwork.id"
                    :artwork="artwork"
                    :reload-only="['artworks']"
                    @click-image="openLightbox(index)"
                />
            </div>

            <PublicPagination :links="artworks.meta.links" />
        </div>

        <!-- Lightbox Modal -->
        <Transition name="fade">
            <div
                v-if="activeArtwork"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-center"
                @click.self="closeLightbox"
            >
                <div
                    class="absolute inset-0 bg-scrim backdrop-blur-3xl"
                    @click="closeLightbox"
                ></div>

                <div
                    class="relative z-10 flex h-full w-full flex-col overflow-hidden"
                >
                    <!-- Nav Controls -->
                    <button
                        @click="prevImage"
                        class="absolute top-1/2 left-4 z-20 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white hover:bg-white/20 focus-visible:outline-white sm:left-10"
                        aria-label="Previous image"
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
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>

                    <button
                        @click="nextImage"
                        class="absolute top-1/2 right-4 z-20 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white hover:bg-white/20 focus-visible:outline-white sm:right-10"
                        aria-label="Next image"
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
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>

                    <button
                        @click="closeLightbox"
                        class="absolute top-4 right-4 z-20 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 focus-visible:outline-white sm:top-10 sm:right-10"
                        aria-label="Close lightbox"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>

                    <!-- Image Display -->
                    <div
                        class="relative flex flex-1 items-center justify-center overflow-auto p-4 sm:p-10"
                        @click.self="closeLightbox"
                    >
                        <div
                            v-if="activeArtwork.media_withheld"
                            class="relative m-auto h-[60vh] w-full max-w-md"
                        >
                            <NsfwGate
                                withheld
                                :artwork-id="activeArtwork.id"
                                :reload-only="['artworks']"
                                variant="detail"
                            />
                        </div>
                        <div
                            v-else
                            class="relative m-auto inline-block transition-all duration-300"
                        >
                            <img
                                v-if="activeArtwork.media_urls?.display"
                                :src="activeArtwork.media_urls.display.src"
                                :srcset="
                                    activeArtwork.media_urls.display.srcset
                                "
                                :alt="
                                    activeArtwork.alt_text ||
                                    activeArtwork.title
                                "
                                :class="[
                                    'rounded-sm transition-all duration-500',
                                    isZoomed
                                        ? 'max-w-none'
                                        : 'max-h-[85vh] max-w-full object-contain',
                                ]"
                            />
                            <!-- Invisible overlay to discourage casual image saving -->
                            <div
                                class="absolute inset-0 z-10"
                                :class="
                                    isZoomed
                                        ? 'cursor-zoom-out'
                                        : 'cursor-zoom-in'
                                "
                                @click="toggleZoom"
                            ></div>
                        </div>
                    </div>

                    <!-- Caption -->
                    <div
                        class="relative z-20 bg-scrim pt-4 pb-10 text-center backdrop-blur-sm"
                    >
                        <h2
                            class="font-display text-2xl wrap-break-word text-white"
                        >
                            {{ activeArtwork.title }}
                        </h2>
                        <Link
                            :href="`/art/${activeArtwork.id}`"
                            class="mt-2 inline-block text-stone-300 hover:text-white focus-visible:outline-white"
                        >
                            View Details &rarr;
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </PublicLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
