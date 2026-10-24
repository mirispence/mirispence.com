<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/BookController';
import BookForm, {
    type BookFormData,
    type GalleryOption,
} from '@/components/admin/BookForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    tags: Array<any>;
    galleries: GalleryOption[];
}>();

const form = useForm<BookFormData>({
    title: '',
    description: '',
    publish_status: 'published',
    featured_flag: false,
    release_date: '',
    external_links: [],
    tags: [],
    gallery_id: null,
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

            <BookForm
                :form="form"
                :tags="tags"
                :galleries="galleries"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>
