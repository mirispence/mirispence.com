<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { useNSFWPreference } from '@/composables/useNSFWPreference';
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
    isRevealed: boolean;
}>();

defineEmits<{
    (e: 'reveal'): void;
    (e: 'click-image'): void;
}>();

const { nsfwAlwaysReveal, setPreference } = useNSFWPreference();
</script>

<template>
    <Card class="group overflow-hidden border-none bg-white">
        <div class="relative aspect-[4/5] overflow-hidden">
            <template v-if="artwork.nsfw_flag && !isRevealed && !nsfwAlwaysReveal">
                <div
                    @click.stop="$emit('click-image')"
                    class="absolute inset-0 z-10 flex cursor-pointer flex-col items-center justify-center bg-panel/60 p-6 text-center backdrop-blur-3xl"
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
                    <div class="mt-4 flex items-center gap-2" @click.stop>
                        <input
                            type="checkbox"
                            :id="`nsfw-pref-${artwork.id}`"
                            :checked="nsfwAlwaysReveal"
                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                            @change="setPreference(($event.target as HTMLInputElement).checked)"
                        />
                        <label :for="`nsfw-pref-${artwork.id}`" class="text-[10px] font-bold text-foreground">
                            Show all NSFW artwork this session
                        </label>
                    </div>
                </div>
                <img
                    v-if="artwork.media_urls?.grid"
                    :src="artwork.media_urls.grid.src"
                    class="h-full w-full scale-110 object-cover blur-2xl grayscale transition-transform duration-700 group-hover:scale-125"
                />
            </template>
            <template v-else>
                <img
                    v-if="artwork.media_urls?.grid"
                    :src="artwork.media_urls.grid.src"
                    :srcset="artwork.media_urls.grid.srcset"
                    :alt="artwork.alt_text || artwork.title"

                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110 cursor-pointer"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    @click="$emit('click-image')"
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
