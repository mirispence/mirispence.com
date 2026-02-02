<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/GalleryController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    gallery: any;
}>();

const form = useForm({
    _method: 'PUT',
    name: props.gallery.name,
    description: props.gallery.description,
    sort_order: props.gallery.sort_order,
    publish_status: props.gallery.publish_status,
    cover: null as File | null,
});

const submit = () => {
    form.post(update.url(props.gallery.id));
};
</script>

<template>
    <Head title="Edit Gallery" />

    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Edit Gallery
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
                            for="name"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Name</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.name"
                                type="text"
                                name="name"
                                id="name"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
                            <div
                                v-if="form.errors.name"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Description</label
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
                            <div v-if="gallery.thumb_url" class="mb-4">
                                <img
                                    :src="gallery.thumb_url"
                                    alt="Current Cover"
                                    class="h-48 w-auto rounded-md object-cover"
                                />
                            </div>
                            <input
                                @input="form.cover = $event.target.files[0]"
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
                            for="sort_order"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Sort Order</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.sort_order"
                                type="number"
                                name="sort_order"
                                id="sort_order"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
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
