<script setup lang="ts">
import { index, update } from '@/actions/App/Http/Controllers/Admin/UserController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps<{
    user: any;
    roles: Array<any>;
}>();

const form = useForm({
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
                            <label for="email" class="form-label">Email</label>
                            <div class="form-field">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    id="email"
                                    class="control"
                                />
                                <div v-if="form.errors.email" class="error">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <label for="password">Password</label>
                                <p class="text-xs text-gray-500 font-normal">Leave blank to keep current password</p>
                            </div>
                            <div class="form-field">
                                <input
                                    v-model="form.password"
                                    type="password"
                                    id="password"
                                    class="control"
                                />
                                <div v-if="form.errors.password" class="error">
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="form-field">
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    id="password_confirmation"
                                    class="control"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="role" class="form-label">Role</label>
                            <div class="form-field">
                                <select
                                    v-model="form.role"
                                    id="role"
                                    class="control control-select"
                                >
                                    <option
                                        v-for="role in roles"
                                        :key="role.id"
                                        :value="role.name"
                                    >
                                        {{ role.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.role" class="error">
                                    {{ form.errors.role }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <div class="actions-bar-inner">
                        <Link
                            :href="index.url()"
                            class="btn-secondary"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Updating...' : 'Update User' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
