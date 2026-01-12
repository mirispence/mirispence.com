<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/TagController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'artwork',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Tag" />

    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Create Tag
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
                            for="type"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Type</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.type"
                                id="type"
                                name="type"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            >
                                <option value="artwork">Artwork</option>
                                <option value="book">Book</option>
                                <option value="both">Both</option>
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
