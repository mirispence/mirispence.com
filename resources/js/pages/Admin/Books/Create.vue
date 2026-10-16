<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/BookController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface GalleryOption {
    id: number;
    name: string;
}

defineProps<{
    tags: Array<any>;
    galleries: GalleryOption[];
}>();

const form = useForm({
    title: '',
    description: '',
    publish_status: 'published',
    featured_flag: false,
    release_date: '',
    external_links: [],
    tags: [],
    gallery_id: null as number | null,
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Book" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Book</h2>
            </div>

            <form @submit.prevent="submit" class="mt-8">
                <div class="admin-card">
                    <div class="admin-card-body form-stack">
                        <div class="form-row">
                            <label for="title" class="form-label">Title</label>
                            <div class="form-field">
                                <input
                                    v-model="form.title"
                                    type="text"
                                    id="title"
                                    class="control"
                                />
                                <div v-if="form.errors.title" class="error">
                                    {{ form.errors.title }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="description" class="form-label"
                                >Description (Markdown)</label
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
                            <label for="gallery_id" class="form-label"
                                >Cover Gallery</label
                            >
                            <div class="form-field">
                                <select
                                    v-model="form.gallery_id"
                                    id="gallery_id"
                                    class="control control-select"
                                >
                                    <option :value="null">No gallery</option>
                                    <option
                                        v-for="gallery in galleries"
                                        :key="gallery.id"
                                        :value="gallery.id"
                                    >
                                        {{ gallery.name }}
                                    </option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    The book's cover is this gallery's cover
                                    artwork.
                                </p>
                                <div
                                    v-if="form.errors.gallery_id"
                                    class="error"
                                >
                                    {{ form.errors.gallery_id }}
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

                        <div class="form-row">
                            <label for="tags" class="form-label">Tags</label>
                            <div class="form-field">
                                <select
                                    v-model="form.tags"
                                    id="tags"
                                    multiple
                                    class="control control-multiselect"
                                >
                                    <option
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        :value="tag.id"
                                    >
                                        {{ tag.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="release_date" class="form-label"
                                >Release Date</label
                            >
                            <div class="form-field">
                                <input
                                    v-model="form.release_date"
                                    type="date"
                                    id="release_date"
                                    class="control"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="form-label">Flags</label>
                            <div class="form-field space-y-3">
                                <div class="flex items-center">
                                    <input
                                        v-model="form.featured_flag"
                                        id="featured_flag"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label
                                        for="featured_flag"
                                        class="ml-2 block text-sm text-gray-900"
                                        >Featured</label
                                    >
                                </div>
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
