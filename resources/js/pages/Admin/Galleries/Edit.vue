<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/GalleryController';
import GalleryForm, {
    type GalleryFormData,
} from '@/components/admin/GalleryForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface CoverCandidate {
    id: number;
    title: string;
    thumb_url: string;
}

const props = defineProps<{
    gallery: any;
    coverCandidates: CoverCandidate[];
}>();

const form = useForm<GalleryFormData>({
    name: props.gallery.name,
    description: props.gallery.description,
    sort_order: props.gallery.sort_order,
    publish_status: props.gallery.publish_status,
    cover_artwork_id: (props.gallery.cover_artwork_id ?? null) as number | null,
});

const selectedCover = computed(
    () =>
        props.coverCandidates.find((c) => c.id === form.cover_artwork_id) ??
        null,
);

const submit = () => {
    form.put(update.url(props.gallery.id));
};
</script>

<template>
    <Head title="Edit Gallery" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Gallery</h2>
            </div>

            <GalleryForm :form="form" @submit="submit">
                <template #cover>
                    <div class="form-row">
                        <label for="cover_artwork_id" class="form-label"
                            >Cover</label
                        >
                        <div class="form-field">
                            <div v-if="selectedCover?.thumb_url" class="mb-4">
                                <img
                                    :src="selectedCover.thumb_url"
                                    :alt="selectedCover.title"
                                    class="max-h-[320px] w-auto rounded-lg bg-gray-50 object-contain ring-1 ring-gray-200"
                                />
                            </div>
                            <select
                                v-if="coverCandidates.length > 0"
                                v-model="form.cover_artwork_id"
                                id="cover_artwork_id"
                                class="control control-select"
                            >
                                <option :value="null">No cover</option>
                                <option
                                    v-for="candidate in coverCandidates"
                                    :key="candidate.id"
                                    :value="candidate.id"
                                >
                                    {{ candidate.title }}
                                </option>
                            </select>
                            <p v-else class="text-sm text-gray-500">
                                This gallery has no artworks yet. Add artworks
                                to it to choose a cover.
                            </p>
                            <div
                                v-if="form.errors.cover_artwork_id"
                                class="error"
                            >
                                {{ form.errors.cover_artwork_id }}
                            </div>
                        </div>
                    </div>
                </template>
            </GalleryForm>
        </div>
    </AdminLayout>
</template>
