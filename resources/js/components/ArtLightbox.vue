<script setup lang="ts">
import { show as artworkShow } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import GatedArtworkImage from '@/components/GatedArtworkImage.vue';
import { Dialog, DialogClose } from '@/components/ui/dialog';
import type { PublicArtwork } from '@/types/publicArtwork';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, X } from 'lucide-vue-next';
// The shadcn DialogContent/Title wrappers bring their own card chrome and type
// styles, so the full-screen lightbox uses the Reka primitives under them.
import {
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogTitle,
} from 'reka-ui';
import { computed, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        /** The current page of results; prev/next wrap around within it. */
        artworks: PublicArtwork[];
        /** Page props the gate reloads after consent. */
        reloadOnly?: string[];
        /** Prev/next buttons and arrow keys. Off for a single artwork. */
        navigation?: boolean;
        /** "View details" link to the artwork page. Off on the artwork page itself. */
        detailsLink?: boolean;
    }>(),
    { reloadOnly: () => [], navigation: true, detailsLink: true },
);

/** Index into `artworks` of the open artwork; null when closed. */
const index = defineModel<number | null>('index', { default: null });

const isZoomed = ref(false);
// No DialogTrigger: the cards open the lightbox, so remember what had focus
// (the card's image button) and hand focus back to it on close.
let returnFocusTo: HTMLElement | null = null;

const active = computed(() =>
    index.value === null ? null : (props.artworks[index.value] ?? null),
);
const canNavigate = computed(
    () => props.navigation && props.artworks.length > 1,
);

watch(index, (now, before) => {
    isZoomed.value = false;
    if (now !== null && before === null) {
        returnFocusTo =
            document.activeElement instanceof HTMLElement
                ? document.activeElement
                : null;
    }
});

const close = () => {
    index.value = null;
};

const onOpenChange = (open: boolean) => {
    if (!open) close();
};

const step = (delta: number) => {
    if (index.value === null || !canNavigate.value) return;
    const count = props.artworks.length;
    index.value = (index.value + delta + count) % count;
};

const onKeydown = (e: KeyboardEvent) => {
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        step(1);
    } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        step(-1);
    }
};

const onCloseAutoFocus = (e: Event) => {
    e.preventDefault();
    returnFocusTo?.focus();
    returnFocusTo = null;
};

// Near-opaque fill so the icon stays readable where a control overlaps a bright image.
const iconButton =
    'rounded-full border border-stone-400 bg-scrim text-white transition-colors hover:bg-stone-700 focus-visible:outline-white';
</script>

<template>
    <Dialog :open="active !== null" @update:open="onOpenChange">
        <DialogPortal>
            <DialogOverlay
                class="fixed inset-0 z-[100] bg-scrim backdrop-blur-xl data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:animate-in data-[state=open]:fade-in-0"
            />
            <DialogContent
                v-if="active"
                aria-modal="true"
                class="fixed inset-0 z-[100] flex flex-col outline-none data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:animate-in data-[state=open]:fade-in-0"
                @keydown="onKeydown"
                @close-auto-focus="onCloseAutoFocus"
            >
                <DialogClose
                    :class="[
                        iconButton,
                        'absolute top-4 right-4 z-20 p-3 sm:top-10 sm:right-10',
                    ]"
                    aria-label="Close"
                >
                    <X class="h-6 w-6" aria-hidden="true" />
                </DialogClose>

                <template v-if="canNavigate">
                    <button
                        type="button"
                        :class="[
                            iconButton,
                            'absolute top-1/2 left-4 z-20 -translate-y-1/2 p-3 sm:left-10 sm:p-4',
                        ]"
                        aria-label="Previous artwork"
                        @click="step(-1)"
                    >
                        <ChevronLeft class="h-8 w-8" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            iconButton,
                            'absolute top-1/2 right-4 z-20 -translate-y-1/2 p-3 sm:right-10 sm:p-4',
                        ]"
                        aria-label="Next artwork"
                        @click="step(1)"
                    >
                        <ChevronRight class="h-8 w-8" aria-hidden="true" />
                    </button>
                </template>

                <div
                    class="relative flex flex-1 items-center justify-center overflow-auto p-4 sm:p-10"
                    @click.self="close"
                >
                    <GatedArtworkImage
                        :key="active.id"
                        :artwork="active"
                        conversion="display"
                        variant="detail"
                        :reload-only="reloadOnly"
                        withheld-class="relative m-auto h-[60vh] w-full max-w-md"
                        frame-class="m-auto inline-block"
                        empty-class="text-stone-300 italic"
                        :action-label="isZoomed ? 'Zoom out' : 'Zoom in'"
                        :pressed="isZoomed"
                        :action-class="`focus-visible:outline-white ${isZoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'}`"
                        @action="isZoomed = !isZoomed"
                        v-slot="{ image }"
                    >
                        <img
                            :src="image.src"
                            :srcset="image.srcset"
                            :alt="active.alt_text || active.title"
                            sizes="100vw"
                            :class="[
                                'rounded-sm',
                                isZoomed
                                    ? 'max-w-none'
                                    : 'max-h-[75vh] max-w-full object-contain',
                            ]"
                        />
                    </GatedArtworkImage>
                </div>

                <div class="relative z-20 px-4 pt-4 pb-10 text-center">
                    <DialogTitle
                        class="font-display text-2xl leading-tight wrap-break-word text-white"
                    >
                        {{ active.title }}
                    </DialogTitle>
                    <DialogDescription
                        v-if="canNavigate"
                        class="mt-1 text-sm text-stone-300"
                    >
                        {{ (index ?? 0) + 1 }} of {{ artworks.length }}
                    </DialogDescription>
                    <DialogDescription v-else class="sr-only">
                        Enlarged artwork
                    </DialogDescription>
                    <Link
                        v-if="detailsLink"
                        :href="artworkShow.url(active.slug)"
                        class="mt-2 inline-block font-semibold text-stone-300 underline-offset-4 hover:text-white hover:underline focus-visible:outline-white"
                    >
                        View details &rarr;
                    </Link>
                </div>
            </DialogContent>
        </DialogPortal>
    </Dialog>
</template>
