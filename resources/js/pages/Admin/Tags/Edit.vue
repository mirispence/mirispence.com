<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/TagController';
import TagForm, { type TagFormData } from '@/components/admin/TagForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    tag: any;
}>();

const form = useForm<TagFormData>({
    _method: 'PUT',
    name: props.tag.name,
    type: props.tag.type,
});

const submit = () => {
    form.post(update.url(props.tag.id));
};
</script>

<template>
    <Head title="Edit Tag" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Tag</h2>
            </div>

            <TagForm :form="form" @submit="submit" />
        </div>
    </AdminLayout>
</template>
