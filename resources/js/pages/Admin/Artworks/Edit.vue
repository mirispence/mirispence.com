<script setup lang="ts">
import {
    regenerate as regenerateAction,
    update,
} from '@/actions/App/Http/Controllers/Admin/ArtworkController';
import { show as showOriginal } from '@/actions/App/Http/Controllers/Admin/OriginalMediaController';
import ArtworkForm, {
    type ArtworkFormData,
} from '@/components/admin/ArtworkForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    artwork: any;
    originalMediaId: number | null;
    galleries: Array<any>;
    tags: Array<any>;
}>();

const form = useForm<ArtworkFormData>({
    _method: 'PUT',
    title: props.artwork.title,
    description: props.artwork.description,
    alt_text: props.artwork.alt_text,
    created_on: props.artwork.created_on,
    publish_status: props.artwork.publish_status,
    nsfw_flag: props.artwork.nsfw_flag,
    featured_flag: props.artwork.featured_flag,
    galleries: props.artwork.galleries.map((g: any) => g.id),
    tags: props.artwork.tags.map((t: any) => t.id),
    image: null,
});

const originalUrl =
    props.originalMediaId !== null
        ? showOriginal.url(props.originalMediaId)
        : null;

const submit = () => {
    form.post(update.url(props.artwork.id));
};

const regenerate = () => {
    router.post(regenerateAction.url(props.artwork.id));
};
</script>

<template>
    <Head title="Edit Artwork" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Artwork</h2>
            </div>

            <ArtworkForm
                :form="form"
                :galleries="galleries"
                :tags="tags"
                @submit="submit"
            >
                <template #image-preview>
                    <div
                        v-if="artwork.thumb_url"
                        class="mb-6 rounded-lg p-4 ring-1 ring-gray-200"
                    >
                        <div class="flex flex-col gap-6 sm:flex-row">
                            <div class="sm:w-1/3">
                                <img
                                    :src="artwork.thumb_url"
                                    alt="Current Image"
                                    class="max-h-[320px] w-full rounded-lg bg-gray-50 object-contain ring-1 ring-gray-200"
                                />
                            </div>
                            <div class="flex-1 space-y-4">
                                <div>
                                    <span
                                        class="mb-1 block text-sm font-semibold text-gray-900"
                                        >Status</span
                                    >
                                    <span
                                        :class="[
                                            'badge',
                                            artwork.image_status === 'ready'
                                                ? 'badge-green'
                                                : 'badge-gray',
                                        ]"
                                    >
                                        {{ artwork.image_status_label }}
                                    </span>
                                </div>

                                <div v-if="artwork.image_error" class="error">
                                    Error: {{ artwork.image_error }}
                                </div>

                                <div class="flex flex-wrap gap-4">
                                    <button
                                        type="button"
                                        @click="regenerate"
                                        class="btn-link"
                                    >
                                        Regenerate
                                    </button>
                                    <a
                                        v-if="originalUrl"
                                        :href="originalUrl"
                                        target="_blank"
                                        rel="noopener"
                                        class="btn-link"
                                    >
                                        Open Original
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </ArtworkForm>
        </div>
    </AdminLayout>
</template>
