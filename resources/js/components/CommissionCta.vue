<script setup lang="ts">
import { create as contactCreate } from '@/actions/App/Http/Controllers/Public/ContactController';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';

// The one commission call to action on the public site (#53).
// --accent (pink) is reserved for it.
const props = withDefaults(
    defineProps<{
        size?: 'default' | 'compact';
        class?: HTMLAttributes['class'];
    }>(),
    { size: 'default' },
);
</script>

<template>
    <Button
        as-child
        variant="accent"
        :size="props.size === 'compact' ? 'sm' : 'lg'"
        :class="cn(props.size === 'default' && 'px-8', props.class)"
    >
        <Link :href="contactCreate.url({ query: { type: 'commission' } })">
            Commissions
        </Link>
    </Button>
</template>
