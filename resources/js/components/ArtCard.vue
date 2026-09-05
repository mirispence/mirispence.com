<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import NsfwGate from '@/components/NsfwGate.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    artwork: {
        id: number;
        title: string;
        slug: string;
        image_url: string;
        media_urls: any;
        signed_urls: any;
        alt_text: string;
        created_on: string;
        nsfw_flag: boolean;
    };
    /** Omit to let the gate track reveal state itself; pass when a sibling view (e.g. a lightbox) must stay in sync. */
    isRevealed?: boolean;
}>();

defineEmits<{
    (e: 'reveal'): void;
    (e: 'click-image'): void;
}>();
</script>

<template>
    <Card class="group overflow-hidden border-none bg-white">
        <div class="relative aspect-[4/5] overflow-hidden">
            <NsfwGate
                :nsfw="artwork.nsfw_flag"
                :model-value="isRevealed"
                variant="card"
                @reveal="$emit('reveal')"
                v-slot="{ revealed }"
            >
                <img
                    v-if="artwork.media_urls?.grid"
                    :src="artwork.media_urls.grid.src"
                    :srcset="revealed ? artwork.media_urls.grid.srcset : undefined"
                    :alt="artwork.alt_text || artwork.title"
                    class="h-full w-full object-cover transition-all duration-700"
                    :class="revealed ? 'group-hover:scale-110' : 'scale-110 blur-2xl grayscale'"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                />
                <div
                    v-else
                    class="flex h-full items-center justify-center bg-panel font-heading text-muted-foreground italic"
                >
                    No Image available
                </div>

                <template v-if="revealed">
                    <!-- Invisible overlay to discourage casual image saving -->
                    <div
                        class="absolute inset-0 z-[5] cursor-pointer"
                        @click="$emit('click-image')"
                    ></div>
                    <div
                        class="absolute inset-x-0 bottom-0 z-10 translate-y-full bg-gradient-to-t from-black/60 to-transparent p-6 transition-transform duration-300 group-hover:translate-y-0"
                    >
                        <Link :href="`/art/${artwork.slug}`">
                            <Button
                                variant="accent"
                                size="sm"
                                class="w-full rounded-full"
                            >
                                View Details
                            </Button>
                        </Link>
                    </div>
                </template>
            </NsfwGate>
        </div>
        <div class="p-6">
            <h3
                class="line-clamp-1 font-heading text-xl font-bold text-foreground transition-colors group-hover:text-primary"
            >
                <Link :href="`/art/${artwork.slug}`">
                    {{ artwork.title }}
                </Link>
            </h3>
            <p
                v-if="artwork.created_on"
                class="mt-1 text-sm text-[10px] font-medium tracking-widest text-muted-foreground uppercase"
            >
                {{ artwork.created_on }}
            </p>
        </div>
    </Card>
</template>
