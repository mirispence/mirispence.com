<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/ArtworkController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    galleries: Array<any>;
    tags: Array<any>;
}>();

const form = useForm<{
    title: string;
    description: string;
    alt_text: string;
    created_on: string;
    publish_status: string;
    nsfw_flag: boolean;
    featured_flag: boolean;
    galleries: number[];
    tags: number[];
    image: File | null;
}>({
    title: '',
    description: '',
    alt_text: '',
    created_on: '',
    publish_status: 'published',
    nsfw_flag: false,
    featured_flag: false,
    galleries: [],
    tags: [],
    image: null,
});

const submit = () => {
    form.post(store.url());
};

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.image = target.files[0];
    }
};
</script>

<template>
    <Head title="Create Artwork" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Artwork</h2>
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
                            <label for="description" class="form-label">Description (Markdown)</label>
                            <div class="form-field">
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="control control-textarea"
                                ></textarea>
                                <div v-if="form.errors.description" class="error">
                                    {{ form.errors.description }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="image" class="form-label">Image</label>
                            <div class="form-field">
                                <input
                                    @input="handleFileUpload"
                                    type="file"
                                    id="image"
                                    class="control-file"
                                />
                                <div v-if="form.errors.image" class="error">
                                    {{ form.errors.image }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="publish_status" class="form-label">Status</label>
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
                            <label for="galleries" class="form-label">Galleries</label>
                            <div class="form-field">
                                <select
                                    v-model="form.galleries"
                                    id="galleries"
                                    multiple
                                    class="control control-multiselect"
                                >
                                    <option
                                        v-for="gallery in galleries"
                                        :key="gallery.id"
                                        :value="gallery.id"
                                    >
                                        {{ gallery.name }}
                                    </option>
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
                            <label class="form-label">Flags</label>
                            <div class="form-field space-y-3">
                                <div class="flex items-center">
                                    <input
                                        v-model="form.nsfw_flag"
                                        id="nsfw_flag"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="nsfw_flag" class="ml-2 block text-sm text-gray-900">NSFW</label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        v-model="form.featured_flag"
                                        id="featured_flag"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="featured_flag" class="ml-2 block text-sm text-gray-900">Featured</label>
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
