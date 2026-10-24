<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/GalleryController';
import GalleryForm, {
    type GalleryFormData,
} from '@/components/admin/GalleryForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm<GalleryFormData>({
    name: '',
    description: '',
    sort_order: 0,
    publish_status: 'published',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Gallery" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Gallery</h2>
            </div>

            <GalleryForm :form="form" @submit="submit" />
        </div>
    </AdminLayout>
</template>
