<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/UserController';
import UserForm, { type UserFormData } from '@/components/admin/UserForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    roles: Array<any>;
}>();

const form = useForm<UserFormData>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create User" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create User</h2>
            </div>

            <UserForm :form="form" :roles="roles" @submit="submit" />
        </div>
    </AdminLayout>
</template>
