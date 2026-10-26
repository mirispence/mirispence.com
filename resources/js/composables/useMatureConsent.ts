import { store } from '@/actions/App/Http/Controllers/Public/MatureConsentController';
import { router } from '@inertiajs/vue3';

/**
 * Consent to see mature (nsfw_flag) artwork. The server is the source of truth
 * (ADR-0004): it withholds media URLs until the session consents, so revealing
 * posts consent and then reloads the affected props (`only`).
 */
export function useMatureConsent() {
    const send = (
        data: { artwork_id: number } | { all: true },
        only: string[] = [],
    ) =>
        router.post(store.url(), data, {
            preserveScroll: true,
            preserveState: true,
            only,
        });

    return {
        /** Reveal one artwork for the rest of the session. */
        reveal: (artworkId: number, only?: string[]) =>
            send({ artwork_id: artworkId }, only),
        /** Reveal every mature artwork for the rest of the session. */
        revealAll: (only?: string[]) => send({ all: true }, only),
    };
}
