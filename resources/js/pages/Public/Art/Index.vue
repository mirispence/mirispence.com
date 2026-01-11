<script setup lang="ts">
import ArtCard from '@/components/ArtCard.vue';
import FormSelect from '@/components/FormSelect.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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

const revealNSFW = (id: number) => {
    revealedImages.value.add(id);
};

const isRevealed = (id: number) => {
    return revealedImages.value.has(id);
};

watch(selectedGallery, (value) => {
    router.get(
        '/art',
        { gallery: value },
        { preserveState: true, replace: true },
    );
});
</script>

<template>
    <Head title="Artworks" />

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
                    v-for="artwork in artworks.data"
                    :key="artwork.id"
                    :artwork="artwork"
                    :is-revealed="isRevealed(artwork.id)"
                    @reveal="revealNSFW(artwork.id)"
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
    </PublicLayout>
</template>
