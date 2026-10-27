<script setup lang="ts">
import { show as artworkShow } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import GatedArtworkImage from '@/components/GatedArtworkImage.vue';
import type { PublicArtwork } from '@/types/publicArtwork';
import { Link } from '@inertiajs/vue3';

defineProps<{
    artwork: PublicArtwork;
    /** Page props to reload after the visitor reveals this artwork. */
    reloadOnly?: string[];
    /**
     * `@click-image`: the image opens the lightbox. Declared as a prop (not an
     * emit) so the card renders the image button only when a page listens;
     * otherwise it would be a tab stop that does nothing.
     */
    onClickImage?: () => void;
}>();
</script>

<template>
    <article class="group">
        <div class="relative aspect-[4/5] overflow-hidden rounded-sm bg-muted">
            <GatedArtworkImage
                :artwork="artwork"
                conversion="grid"
                variant="card"
                :reload-only="reloadOnly"
                withheld-class="h-full w-full"
                frame-class="h-full w-full"
                empty-class="flex h-full items-center justify-center text-muted-foreground italic"
                :action-label="`View ${artwork.title} larger`"
                opens-dialog
                action-class="cursor-zoom-in"
                :on-action="onClickImage"
                v-slot="{ image }"
            >
                <img
                    :src="image.src"
                    :srcset="image.srcset"
                    :alt="artwork.alt_text || artwork.title"
                    class="h-full w-full object-cover"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                />
            </GatedArtworkImage>
        </div>
        <h3 class="mt-3 line-clamp-1 text-lg font-semibold text-foreground">
            <Link
                :href="artworkShow.url(artwork.slug)"
                class="underline-offset-4 hover:text-primary hover:underline"
            >
                {{ artwork.title }}
            </Link>
        </h3>
        <p
            v-if="artwork.created_on"
            class="mt-0.5 text-sm text-muted-foreground"
        >
            {{ artwork.created_on }}
        </p>
    </article>
</template>
