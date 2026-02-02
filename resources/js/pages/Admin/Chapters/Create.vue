<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/ChapterController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    books: Array<any>;
    book_id: number | null;
}>();

const form = useForm({
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

            <form @submit.prevent="submit" class="mt-8">
                <div class="admin-card">
                    <div class="admin-card-body form-stack">
                        <div class="form-row">
                            <label for="book_id" class="form-label">Book</label>
                            <div class="form-field">
                                <select
                                    v-model="form.book_id"
                                    id="book_id"
                                    class="control control-select"
                                >
                                    <option value="" disabled>Select a book</option>
                                    <option
                                        v-for="book in books"
                                        :key="book.id"
                                        :value="book.id"
                                    >
                                        {{ book.title }}
                                    </option>
                                </select>
                                <div v-if="form.errors.book_id" class="error">
                                    {{ form.errors.book_id }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="title" class="form-label">Title</label>
                            <div class="form-field">
                                <input
                                    v-model="form.title"
                                    type="text"
                                    id="title"
                                    class="control"
                                />
                                <div v-if="form.errors.title" class="error">
                                    {{ form.errors.title }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="summary" class="form-label">Summary</label>
                            <div class="form-field">
                                <textarea
                                    v-model="form.summary"
                                    id="summary"
                                    rows="3"
                                    class="control control-textarea"
                                ></textarea>
                                <div v-if="form.errors.summary" class="error">
                                    {{ form.errors.summary }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="body_markdown" class="form-label">Body (Markdown)</label>
                            <div class="form-field">
                                <textarea
                                    v-model="form.body_markdown"
                                    id="body_markdown"
                                    rows="10"
                                    class="control control-textarea font-mono"
                                ></textarea>
                                <div v-if="form.errors.body_markdown" class="error">
                                    {{ form.errors.body_markdown }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="order" class="form-label">Order</label>
                            <div class="form-field">
                                <input
                                    v-model="form.order"
                                    type="number"
                                    id="order"
                                    class="control"
                                />
                                <div v-if="form.errors.order" class="error">
                                    {{ form.errors.order }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="form-label">Options</label>
                            <div class="form-field space-y-3">
                                <div class="flex items-center">
                                    <input
                                        v-model="form.is_sample"
                                        id="is_sample"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <label for="is_sample" class="ml-2 block text-sm text-gray-900">Sample Chapter</label>
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
