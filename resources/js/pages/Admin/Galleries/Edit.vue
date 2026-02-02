<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/GalleryController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    gallery: any;
}>();

const form = useForm({
    _method: 'PUT',
    name: props.gallery.name,
    description: props.gallery.description,
    sort_order: props.gallery.sort_order,
    publish_status: props.gallery.publish_status,
    cover: null as File | null,
});

const submit = () => {
    form.post(update.url(props.gallery.id));
};

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.cover = target.files[0];
    }
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
                            <label for="description" class="form-label">Description</label>
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
                            <label for="cover" class="form-label">Cover Image</label>
                            <div class="form-field">
                                <div v-if="gallery.thumb_url" class="mb-4">
                                    <img
                                        :src="gallery.thumb_url"
                                        alt="Current Cover"
                                        class="rounded-lg ring-1 ring-gray-200 max-h-[320px] w-auto object-contain bg-gray-50"
                                    />
                                </div>
                                <input
                                    @input="handleFileUpload"
                                    type="file"
                                    id="cover"
                                    class="control-file"
                                />
                                <div v-if="form.errors.cover" class="error">
                                    {{ form.errors.cover }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <div class="form-field">
                                <input
                                    v-model="form.sort_order"
                                    type="number"
                                    id="sort_order"
                                    class="control"
                                />
                                <div v-if="form.errors.sort_order" class="error">
                                    {{ form.errors.sort_order }}
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
