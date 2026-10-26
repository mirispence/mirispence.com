<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useMatureConsent } from '@/composables/useMatureConsent';
import { computed, useId } from 'vue';

const props = withDefaults(
    defineProps<{
        /** The server withheld this artwork's media (mature, no consent yet). When false, no gate renders. */
        withheld: boolean;
        artworkId: number;
        /** Page props to reload once consent is recorded; empty reloads all. */
        reloadOnly?: string[];
        variant?: 'card' | 'detail';
    }>(),
    { reloadOnly: () => [], variant: 'card' },
);

const consent = useMatureConsent();
const checkboxId = useId();

const revealed = computed(() => !props.withheld);

const reveal = () => consent.reveal(props.artworkId, props.reloadOnly);
const revealAll = () => consent.revealAll(props.reloadOnly);
</script>

<template>
    <div class="relative h-full w-full">
        <slot :revealed="revealed" />

        <div
            v-if="!revealed"
            class="absolute inset-0 z-10 flex cursor-pointer flex-col items-center justify-center text-center backdrop-blur-md"
            :class="variant === 'card' ? 'bg-muted/90 p-6' : 'bg-muted/40 p-8'"
            @click.stop="reveal"
        >
            <div
                :class="
                    variant === 'detail'
                        ? 'max-w-[280px] rounded-lg border border-border bg-card p-8 shadow-card'
                        : ''
                "
            >
                <span
                    v-if="variant === 'card'"
                    class="mb-4 inline-block rounded-full bg-foreground px-4 py-1 text-xs font-semibold text-background"
                >
                    Sensitive Content
                </span>
                <h3 v-else class="mb-2 text-xl font-semibold text-foreground">
                    Sensitive Content
                </h3>

                <p
                    class="font-medium text-foreground"
                    :class="
                        variant === 'card'
                            ? 'mb-6 text-sm'
                            : 'mb-6 text-sm text-muted-foreground'
                    "
                >
                    This artwork may not be suitable for all audiences.
                </p>

                <Button
                    :size="variant === 'card' ? 'sm' : 'lg'"
                    class="px-6 font-semibold"
                    @click.stop="reveal"
                >
                    Reveal Artwork
                </Button>

                <div
                    class="mt-4 flex items-center justify-center gap-2"
                    @click.stop
                >
                    <input
                        :id="checkboxId"
                        type="checkbox"
                        class="h-4 w-4 rounded border-input accent-primary"
                        @change="
                            ($event.target as HTMLInputElement).checked &&
                            revealAll()
                        "
                    />
                    <label
                        :for="checkboxId"
                        class="text-sm font-medium text-foreground"
                    >
                        Show all NSFW artwork this session
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
