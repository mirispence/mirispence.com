<script setup lang="ts">
import { show as artworkShow } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import NsfwGate from '@/components/NsfwGate.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    artwork: {
        id: number;
        title: string;
        slug: string;
        media_urls: any;
        alt_text: string;
        created_on: string;
        nsfw_flag: boolean;
        /** Server withheld the media (mature, no consent yet): show the gate over a neutral placeholder. */
        media_withheld: boolean;
    };
    /** Page props to reload after the visitor reveals this artwork. */
    reloadOnly?: string[];
}>();

defineEmits<{
    (e: 'click-image'): void;
}>();
</script>

<template>
    <article class="group">
        <div class="relative aspect-[4/5] overflow-hidden rounded-sm bg-muted">
            <NsfwGate
                :withheld="artwork.media_withheld"
                :artwork-id="artwork.id"
                :reload-only="reloadOnly"
                variant="card"
                v-slot="{ revealed }"
            >
                <img
                    v-if="artwork.media_urls?.grid"
                    :src="artwork.media_urls.grid.src"
                    :srcset="artwork.media_urls.grid.srcset"
                    :alt="artwork.alt_text || artwork.title"
                    class="h-full w-full object-cover"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                />
                <div
                    v-else-if="!artwork.media_withheld"
                    class="flex h-full items-center justify-center text-muted-foreground italic"
                >
                    No Image available
                </div>

                <!-- Transparent button over the image: opens the lightbox and discourages casual image saving -->
                <button
                    v-if="revealed"
                    type="button"
                    class="absolute inset-0 z-[5] cursor-zoom-in"
                    :aria-label="`View ${artwork.title} larger`"
                    @click="$emit('click-image')"
                ></button>
            </NsfwGate>
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
