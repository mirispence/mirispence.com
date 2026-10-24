<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/ChapterController';
import ChapterForm, {
    type ChapterFormData,
} from '@/components/admin/ChapterForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    books: Array<any>;
    book_id: number | null;
}>();

const form = useForm<ChapterFormData>({
    book_id: props.book_id || '',
    title: '',
    summary: '',
    body_markdown: '',
    order: 1,
    is_sample: false,
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Chapter" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Chapter</h2>
            </div>

            <ChapterForm :form="form" :books="books" @submit="submit" />
        </div>
    </AdminLayout>
</template>
