<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/TagController';
import TagForm, { type TagFormData } from '@/components/admin/TagForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm<TagFormData>({
    name: '',
    type: 'artwork',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Tag" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Tag</h2>
            </div>

            <TagForm :form="form" @submit="submit" />
        </div>
    </AdminLayout>
</template>
