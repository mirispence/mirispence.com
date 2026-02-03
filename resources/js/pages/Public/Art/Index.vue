<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import FormSelect from '@/components/FormSelect.vue';
import { Button } from '@/components/ui/button';
import { useNSFWPreference } from '@/composables/useNSFWPreference';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
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
const revealedImages = ref(new Set<number>());
const { nsfwAlwaysReveal, setPreference } = useNSFWPreference();

const revealNSFW = (id: number) => {
    revealedImages.value.add(id);
};

const isRevealed = (id: number) => {
    return revealedImages.value.has(id);
};

const activeLightboxIndex = ref<number | null>(null);
const isZoomed = ref(false);
const activeArtwork = computed(() => activeLightboxIndex.value !== null ? props.artworks.data[activeLightboxIndex.value] : null);

const openLightbox = (index: number) => {
    const artwork = props.artworks.data[index];
    if (artwork.nsfw_flag && !isRevealed(artwork.id)) {
        // We can optionally check nsfwAlwaysReveal here too if we want to skip reveal
        // But ArtCard handles the click emit, so we just check if it needs manual reveal
        // Use the composable logic check if preferred
        revealNSFW(artwork.id);
    }
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
                        class="font-heading text-5xl font-black tracking-tight text-foreground"
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
                        class="mb-3 block text-xs font-bold tracking-widest text-muted-foreground uppercase"
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
                    :is-revealed="isRevealed(artwork.id)"
                    @reveal="revealNSFW(artwork.id)"
                    @click-image="openLightbox(index)"
                />
            </div>

            <!-- Pagination -->
            <div
                class="mt-20 flex items-center justify-center"
                v-if="artworks.links.length > 3"
            >
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <template v-for="link in artworks.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-bold transition-all"
                            :class="
                                link.active
                                    ? 'bg-primary text-white shadow-lg'
                                    : 'bg-white text-muted-foreground hover:bg-panel hover:text-primary'
                            "
                        >
                            <span v-html="link.label"></span>
                        </Link>
                        <span
                            v-else
                            class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg px-3 text-sm font-medium text-muted-foreground/40"
                            v-html="link.label"
                        ></span>
                    </template>
                </nav>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <Transition name="fade">
            <div
                v-if="activeArtwork"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-center"
                @click.self="closeLightbox"
            >
                <div class="absolute inset-0 bg-panel/95 backdrop-blur-3xl" @click="closeLightbox"></div>

                <div class="relative z-10 flex h-full w-full flex-col overflow-hidden">
                    <!-- Nav Controls -->
                    <button
                        @click="prevImage"
                        class="absolute left-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white hover:bg-white/20 sm:left-10"
                        aria-label="Previous image"
                    >
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button
                        @click="nextImage"
                        class="absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white hover:bg-white/20 sm:right-10"
                        aria-label="Next image"
                    >
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <button
                        @click="closeLightbox"
                        class="absolute right-4 top-4 z-20 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 sm:right-10 sm:top-10"
                        aria-label="Close lightbox"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Image Display -->
                    <div
                        class="relative flex-1 overflow-auto flex items-center justify-center p-4 sm:p-10"
                        @click.self="closeLightbox"
                    >
                        <template v-if="activeArtwork.nsfw_flag && !isRevealed(activeArtwork.id) && !nsfwAlwaysReveal">
                             <div class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-panel/20 backdrop-blur-md">
                                <div class="max-w-[280px] rounded-[2rem] bg-white/80 p-8 text-center shadow-2xl">
                                    <h3 class="mb-2 font-heading text-xl font-bold text-foreground">Age Restricted</h3>
                                    <p class="mb-6 text-sm text-muted-foreground">NSFW Content</p>
                                    <Button
                                        variant="accent"
                                        size="lg"
                                        class="w-full rounded-full font-bold"
                                        @click="revealNSFW(activeArtwork.id)"
                                    >Show Artwork</Button>
                                    <div class="mt-4 flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            id="lb-nsfw-pref"
                                            :checked="nsfwAlwaysReveal"
                                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                            @change="setPreference(($event.target as HTMLInputElement).checked)"
                                        />
                                        <label for="lb-nsfw-pref" class="text-xs font-bold text-foreground">
                                            Show all NSFW artwork this session
                                        </label>
                                    </div>
                                </div>
                             </div>
                        </template>

                        <div
                            class="relative inline-block transition-all duration-300 m-auto"
                            :class="isZoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                            @click="toggleZoom"
                        >
                            <img
                                v-if="activeArtwork.media_urls?.display"
                                :src="activeArtwork.media_urls.display.src"
                                :srcset="activeArtwork.media_urls.display.srcset"
                                :alt="activeArtwork.alt_text || activeArtwork.title"
                                :class="[
                                    'shadow-2xl rounded-lg transition-all duration-500',
                                    activeArtwork.nsfw_flag && !isRevealed(activeArtwork.id) && !nsfwAlwaysReveal ? 'blur-3xl opacity-30 grayscale' : '',
                                    isZoomed ? 'max-w-none' : 'max-h-[85vh] max-w-full object-contain'
                                ]"
                            />
                            <img
                                v-else-if="activeArtwork.image_url"
                                :src="activeArtwork.image_url"
                                :alt="activeArtwork.alt_text || activeArtwork.title"
                                class="shadow-2xl rounded-lg transition-all duration-500"
                                :class="[
                                    activeArtwork.nsfw_flag && !isRevealed(activeArtwork.id) && !nsfwAlwaysReveal? 'blur-3xl opacity-30 grayscale' : '',
                                    isZoomed ? 'max-w-none' : 'max-h-[85vh] max-w-full object-contain'
                                ]"
                            />
                        </div>
                    </div>

                    <!-- Caption -->
                    <div class="relative z-20 pb-10 pt-4 text-center bg-panel/80 backdrop-blur-sm">
                        <h2 class="font-heading text-2xl font-bold text-white">{{ activeArtwork.title }}</h2>
                        <Link
                            :href="`/art/${activeArtwork.id}`"
                            class="mt-2 inline-block text-white/60 hover:text-white"
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
