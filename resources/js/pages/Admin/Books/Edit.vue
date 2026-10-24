<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/BookController';
import BookForm, {
    type BookFormData,
    type GalleryOption,
} from '@/components/admin/BookForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    book: any;
    tags: Array<any>;
    galleries: GalleryOption[];
}>();

const form = useForm<BookFormData>({
    title: props.book.title,
    description: props.book.description,
    publish_status: props.book.publish_status,
    featured_flag: props.book.featured_flag,
    release_date: props.book.release_date,
    external_links: props.book.external_links,
    tags: props.book.tags.map((t: any) => t.id),
    gallery_id: props.book.gallery_id ?? null,
});

const submit = () => {
    form.put(update.url(props.book.id));
};
</script>

<template>
    <Head title="Edit Book" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Book</h2>
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
