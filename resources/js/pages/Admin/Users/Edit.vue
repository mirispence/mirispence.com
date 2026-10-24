<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/UserController';
import UserForm, { type UserFormData } from '@/components/admin/UserForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    user: any;
    roles: Array<any>;
}>();

const form = useForm<UserFormData>({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.roles[0]?.name || 'user',
});

const submit = () => {
    form.put(update.url(props.user.id));
};
</script>

<template>
    <Head title="Edit User" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit User: {{ user.name }}</h2>
            </div>

            <UserForm :form="form" :roles="roles" is-edit @submit="submit" />
        </div>
    </AdminLayout>
</template>
