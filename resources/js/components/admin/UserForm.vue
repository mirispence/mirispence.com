<script setup lang="ts">
import { index } from '@/actions/App/Http/Controllers/Admin/UserController';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

export interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role: string;
    _method?: string;
}

defineProps<{
    form: InertiaForm<UserFormData>;
    roles: Array<any>;
    isEdit?: boolean;
}>();
defineEmits<{ submit: [] }>();
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="mt-8">
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
                    <div v-if="isEdit" class="form-label">
                        <label for="password">Password</label>
                        <p class="text-xs font-normal text-gray-500">
                            Leave blank to keep current password
                        </p>
                    </div>
                    <label v-else for="password" class="form-label"
                        >Password</label
                    >
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
                    <label for="password_confirmation" class="form-label"
                        >Confirm Password</label
                    >
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
                <Link :href="index.url()" class="btn-secondary"> Cancel </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    <template v-if="isEdit">
                        {{ form.processing ? 'Updating...' : 'Update User' }}
                    </template>
                    <template v-else>
                        {{ form.processing ? 'Creating...' : 'Create User' }}
                    </template>
                </button>
            </div>
        </div>
    </form>
</template>
