import { ref, onMounted, watch } from 'vue';

const ALWAYS_REVEAL_KEY = 'miri_art_nsfw_reveal';

const nsfwAlwaysReveal = ref(false);

export function useNSFWPreference() {
    onMounted(() => {
        const stored = sessionStorage.getItem(ALWAYS_REVEAL_KEY);
        if (stored === 'true') {
            nsfwAlwaysReveal.value = true;
        }
    });

    const setPreference = (value: boolean) => {
        nsfwAlwaysReveal.value = value;
        sessionStorage.setItem(ALWAYS_REVEAL_KEY, value ? 'true' : 'false');
    };

    return {
        nsfwAlwaysReveal,
        setPreference
    };
}
