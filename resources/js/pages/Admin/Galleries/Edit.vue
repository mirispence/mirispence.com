<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/GalleryController';
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

const form = useForm({
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

            <form @submit.prevent="submit" class="mt-8">
                <div class="admin-card">
                    <div class="admin-card-body form-stack">
                        <div class="form-row">
                            <label for="name" class="form-label">Name</label>
                            <div class="form-field">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    class="control"
                                />
                                <div v-if="form.errors.name" class="error">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="description" class="form-label"
                                >Description</label
                            >
                            <div class="form-field">
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="control control-textarea"
                                ></textarea>
                                <div
                                    v-if="form.errors.description"
                                    class="error"
                                >
                                    {{ form.errors.description }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="cover_artwork_id" class="form-label"
                                >Cover</label
                            >
                            <div class="form-field">
                                <div
                                    v-if="selectedCover?.thumb_url"
                                    class="mb-4"
                                >
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
                                    This gallery has no artworks yet. Add
                                    artworks to it to choose a cover.
                                </p>
                                <div
                                    v-if="form.errors.cover_artwork_id"
                                    class="error"
                                >
                                    {{ form.errors.cover_artwork_id }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="sort_order" class="form-label"
                                >Sort Order</label
                            >
                            <div class="form-field">
                                <input
                                    v-model="form.sort_order"
                                    type="number"
                                    id="sort_order"
                                    class="control"
                                />
                                <div
                                    v-if="form.errors.sort_order"
                                    class="error"
                                >
                                    {{ form.errors.sort_order }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="publish_status" class="form-label"
                                >Status</label
                            >
                            <div class="form-field">
                                <select
                                    v-model="form.publish_status"
                                    id="publish_status"
                                    class="control control-select"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <div class="actions-bar-inner">
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
