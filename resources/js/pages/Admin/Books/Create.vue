<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/BookController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    tags: Array<any>;
}>();

const form = useForm({
    title: '',
    description: '',
    publish_status: 'published',
    featured_flag: false,
    release_date: '',
    external_links: [],
    tags: [],
    cover: null as File | null,
});

const submit = () => {
    form.post(store.url());
};

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.cover = target.files[0];
    }
};
</script>

<template>
    <Head title="Create Book" />

    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Create Book
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
                            for="description"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Description (Markdown)</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <textarea
                                v-model="form.description"
                                id="description"
                                name="description"
                                rows="3"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            ></textarea>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="cover"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Cover Image</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                @input="handleFileUpload"
                                type="file"
                                id="cover"
                                class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none"
                            />
                            <div
                                v-if="form.errors.cover"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.cover }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="publish_status"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Status</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.publish_status"
                                id="publish_status"
                                name="publish_status"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="tags"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Tags</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.tags"
                                id="tags"
                                name="tags"
                                multiple
                                class="block min-h-[100px] w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option
                                    v-for="tag in tags"
                                    :key="tag.id"
                                    :value="tag.id"
                                >
                                    {{ tag.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="release_date"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Release Date</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.release_date"
                                type="date"
                                name="release_date"
                                id="release_date"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Flags</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="mt-2 flex items-center">
                                <input
                                    v-model="form.featured_flag"
                                    id="featured_flag"
                                    name="featured_flag"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label
                                    for="featured_flag"
                                    class="ml-2 block text-sm text-gray-900"
                                    >Featured</label
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
