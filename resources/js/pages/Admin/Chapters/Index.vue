<script setup lang="ts">
import {
    create,
    destroy,
    edit,
    index,
} from '@/actions/App/Http/Controllers/Admin/ChapterController';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    chapters: {
        data: Array<any>;
        links: Array<any>;
    };
    book: any;
    books: Array<any>;
}>();

const form = useForm({
    book_id: props.book ? props.book.id : '',
});

watch(
    () => form.book_id,
    (value) => {
        if (value) {
            router.get(index.url({ book_id: value }));
        } else {
            router.get(index.url());
        }
    },
);

const deleteChapter = (id: number) => {
    if (confirm('Are you sure you want to delete this chapter?')) {
        router.delete(destroy.url(id));
    }
};
</script>

<template>
    <Head title="Chapters" />

    <AdminLayout>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Chapters</h1>
                <p class="mt-2 text-sm text-gray-700">
                    <span v-if="book">Chapters for {{ book.title }}</span>
                    <span v-else>All Chapters</span>
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <Link
                    :href="create.url({ book_id: book ? book.id : null })"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Add Chapter
                </Link>
            </div>
        </div>

        <div class="mt-4">
            <label
                for="book_filter"
                class="block text-sm font-medium text-gray-700"
                >Filter by Book</label
            >
            <select
                v-model="form.book_id"
                id="book_filter"
                class="mt-1 block w-full rounded-md border-gray-300 py-2 pr-10 pl-3 text-base focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none sm:text-sm"
            >
                <option value="">All Books</option>
                <option v-for="b in books" :key="b.id" :value="b.id">
                    {{ b.title }}
                </option>
            </select>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                >
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                >
                                    Title
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Book
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Order
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Sample
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pr-4 pl-3 sm:pr-0"
                                >
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="chapter in chapters.data"
                                :key="chapter.id"
                            >
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                                >
                                    {{ chapter.title }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ chapter.book.title }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ chapter.order }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    <span
                                        v-if="chapter.is_sample"
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                        >Yes</span
                                    >
                                    <span
                                        v-else
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800"
                                        >No</span
                                    >
                                </td>
                                <td
                                    class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                                >
                                    <Link
                                        :href="edit.url(chapter.id)"
                                        class="mr-4 text-indigo-600 hover:text-indigo-900"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="deleteChapter(chapter.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Pagination :links="chapters.links" />
    </AdminLayout>
</template>
