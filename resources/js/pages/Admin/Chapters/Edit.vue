<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/ChapterController';
import ChapterForm, {
    type ChapterFormData,
} from '@/components/admin/ChapterForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    chapter: any;
    books: Array<any>;
}>();

const form = useForm<ChapterFormData>({
    _method: 'PUT',
    book_id: props.chapter.book_id,
    title: props.chapter.title,
    summary: props.chapter.summary,
    body_markdown: props.chapter.body_markdown,
    order: props.chapter.order,
    is_sample: props.chapter.is_sample,
});

const submit = () => {
    form.post(update.url(props.chapter.id));
};
</script>

<template>
    <Head title="Edit Chapter" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Chapter</h2>
            </div>

            <ChapterForm :form="form" :books="books" @submit="submit" />
        </div>
    </AdminLayout>
</template>
