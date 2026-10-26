<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useNSFWPreference } from '@/composables/useNSFWPreference';
import { computed, ref, useId } from 'vue';

const props = withDefaults(
    defineProps<{
        /** Whether this artwork is flagged NSFW at all. When false, always revealed and no gate renders. */
        nsfw: boolean;
        /** Externally tracked reveal state. Omit to let the gate manage its own state (uncontrolled). */
        modelValue?: boolean;
        variant?: 'card' | 'detail';
    }>(),
    { modelValue: undefined, variant: 'card' },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'reveal'): void;
}>();

const { nsfwAlwaysReveal, setPreference } = useNSFWPreference();
const internalRevealed = ref(false);
const checkboxId = useId();

const revealed = computed(
    () =>
        !props.nsfw ||
        (props.modelValue ?? internalRevealed.value) ||
        nsfwAlwaysReveal.value,
);

const reveal = () => {
    internalRevealed.value = true;
    emit('update:modelValue', true);
    emit('reveal');
};
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
                        :checked="nsfwAlwaysReveal"
                        class="h-4 w-4 rounded border-input accent-primary"
                        @change="
                            setPreference(
                                ($event.target as HTMLInputElement).checked,
                            )
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
