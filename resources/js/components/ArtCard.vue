<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';

defineProps<{
    artwork: {
        id: number;
        title: string;
        slug: string;
        image_url: string;
        alt_text: string;
        created_on: string;
        nsfw_flag: boolean;
    };
    isRevealed: boolean;
}>();

defineEmits<{
    (e: 'reveal'): void;
}>();
</script>

<template>
    <Card class="group overflow-hidden border-none bg-white">
        <div class="relative aspect-[4/5] overflow-hidden">
            <template v-if="artwork.nsfw_flag && !isRevealed">
                <div
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-panel/60 p-6 text-center backdrop-blur-3xl"
                >
                    <span
                        class="mb-4 rounded-full bg-accent px-4 py-1 text-[10px] font-black tracking-[0.2em] text-white uppercase shadow-lg"
                    >
                        NSFW Content
                    </span>
                    <p class="mb-6 text-sm font-medium text-foreground">
                        This content may be sensitive.
                    </p>
                    <Button
                        variant="default"
                        size="sm"
                        class="rounded-full bg-primary px-6"
                        @click="$emit('reveal')"
                    >
                        Reveal Artwork
                    </Button>
                </div>
                <img
                    v-if="artwork.image_url"
                    :src="artwork.image_url"
                    class="h-full w-full scale-110 object-cover blur-2xl grayscale transition-transform duration-700 group-hover:scale-125"
                />
            </template>
            <template v-else>
                <img
                    v-if="artwork.image_url"
                    :src="artwork.image_url"
                    :alt="artwork.alt_text || artwork.title"
                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div
                    v-else
                    class="flex h-full items-center justify-center bg-panel font-heading text-muted-foreground italic"
                >
                    No Image available
                </div>
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
