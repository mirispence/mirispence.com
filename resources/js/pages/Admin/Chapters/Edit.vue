<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/ChapterController';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    chapter: any;
    books: Array<any>;
}>();

const form = useForm({
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
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Edit Chapter
                </h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-6">
            <div class="space-y-8 divide-y divide-gray-200">
                <div class="space-y-6 sm:space-y-5">
                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="book_id"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Book</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.book_id"
                                id="book_id"
                                name="book_id"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
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
                            <div
                                v-if="form.errors.book_id"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.book_id }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Title</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.title"
                                type="text"
                                name="title"
                                id="title"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
                            <div
                                v-if="form.errors.title"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.title }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="summary"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Summary</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <textarea
                                v-model="form.summary"
                                id="summary"
                                name="summary"
                                rows="3"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            ></textarea>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="body_markdown"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Body (Markdown)</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <textarea
                                v-model="form.body_markdown"
                                id="body_markdown"
                                name="body_markdown"
                                rows="10"
                                class="block w-full max-w-lg rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            ></textarea>
                            <div
                                v-if="form.errors.body_markdown"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.body_markdown }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="order"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Order</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.order"
                                type="number"
                                name="order"
                                id="order"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Options</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="flex items-center">
                                <input
                                    v-model="form.is_sample"
                                    id="is_sample"
                                    name="is_sample"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label
                                    for="is_sample"
                                    class="ml-2 block text-sm text-gray-900"
                                    >Sample Chapter</label
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Save
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
