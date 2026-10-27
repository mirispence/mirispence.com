<script setup lang="ts">
import NsfwGate from '@/components/NsfwGate.vue';
import type { PublicArtwork, ResponsiveImage } from '@/types/publicArtwork';
import { computed } from 'vue';

/**
 * One artwork image on a public page, mature-gated (ADR-0004): the gate over a
 * placeholder while the server withholds the media, else the image (default
 * slot) under a transparent cover that discourages casual saving, else a
 * "No image available" note.
 */
const props = withDefaults(
    defineProps<{
        artwork: PublicArtwork;
        /** Which conversion the image slot receives. */
        conversion: 'grid' | 'display';
        variant?: 'card' | 'detail';
        /** Page props the gate reloads after consent. */
        reloadOnly?: string[];
        /** Classes for the box the gate fills while the media is withheld. */
        withheldClass?: string;
        /** Classes for the element wrapping the image and its cover. */
        frameClass?: string;
        /** Classes for the "No image available" note. */
        emptyClass?: string;
        /** Accessible name of the cover button. Required with `onAction`. */
        actionLabel?: string;
        /** Sets `aria-pressed` on the cover button (a toggle such as zoom). */
        pressed?: boolean;
        /** The cover button opens a dialog (`aria-haspopup`). */
        opensDialog?: boolean;
        /** Extra classes for the cover button. */
        actionClass?: string;
        /**
         * `@action`: what the cover does when clicked. Declared as a prop (not
         * an emit) so the cover is a button only when a page listens; otherwise
         * it would be a tab stop that does nothing.
         */
        onAction?: () => void;
    }>(),
    {
        variant: 'card',
        reloadOnly: () => [],
        withheldClass: '',
        frameClass: '',
        emptyClass: '',
        actionLabel: undefined,
        // Without this, Vue's boolean casting would render aria-pressed="false".
        pressed: undefined,
        opensDialog: false,
        actionClass: '',
    },
);

const image = computed<ResponsiveImage | null>(
    () => props.artwork.media_urls?.[props.conversion] ?? null,
);

// Focus the cover on click (Safari doesn't) so a dialog can return focus here.
const act = (event: MouseEvent) => {
    (event.currentTarget as HTMLElement).focus();
    props.onAction?.();
};
</script>

<template>
    <div v-if="artwork.media_withheld" :class="withheldClass">
        <NsfwGate
            withheld
            :artwork-id="artwork.id"
            :reload-only="reloadOnly"
            :variant="variant"
        />
    </div>

    <div v-else-if="image" class="relative" :class="frameClass">
        <slot :image="image" />

        <button
            v-if="onAction"
            type="button"
            class="absolute inset-0 z-10 focus-visible:-outline-offset-4"
            :class="actionClass"
            :aria-label="actionLabel"
            :aria-pressed="pressed"
            :aria-haspopup="opensDialog ? 'dialog' : undefined"
            @click="act"
        ></button>
        <div v-else class="absolute inset-0 z-10"></div>
    </div>

    <div v-else :class="emptyClass">No image available</div>
</template>
