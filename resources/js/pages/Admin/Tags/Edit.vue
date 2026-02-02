<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/TagController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    tag: any;
}>();

const form = useForm({
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
                            <label for="type" class="form-label">Type</label>
                            <div class="form-field">
                                <select
                                    v-model="form.type"
                                    id="type"
                                    class="control control-select"
                                >
                                    <option value="artwork">Artwork</option>
                                    <option value="book">Book</option>
                                    <option value="both">Both</option>
                                </select>
                                <div v-if="form.errors.type" class="error">
                                    {{ form.errors.type }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <div class="actions-bar-inner">
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
