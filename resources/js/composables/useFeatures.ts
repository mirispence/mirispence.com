import type { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Feature flags shared by HandleInertiaRequests (ADR-0006). Flags are global,
 * so every page sees the same values.
 */
export function useFeatures() {
    const page = usePage<AppPageProps>();

    return computed(() => page.props.features);
}
