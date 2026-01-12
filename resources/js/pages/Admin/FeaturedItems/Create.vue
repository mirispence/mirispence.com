<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/FeaturedItemController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    artworks: Array<any>;
    books: Array<any>;
}>();

const form = useForm({
    item_type: 'artwork',
    item_id: '',
    display_context: 'home',
    display_order: 1,
});

const items = computed(() => {
    if (form.item_type === 'artwork') {
        return props.artworks;
    } else if (form.item_type === 'book') {
        return props.books;
    }
    return [];
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Featured Item" />

    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Create Featured Item
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
                            for="item_type"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Item Type</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.item_type"
                                id="item_type"
                                name="item_type"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            >
                                <option value="artwork">Artwork</option>
                                <option value="book">Book</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="item_id"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Item</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.item_id"
                                id="item_id"
                                name="item_id"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            >
                                <option value="" disabled>
                                    Select an item
                                </option>
                                <option
                                    v-for="item in items"
                                    :key="item.id"
                                    :value="item.id"
                                >
                                    {{ item.title }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.item_id"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.item_id }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="display_context"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Context</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <select
                                v-model="form.display_context"
                                id="display_context"
                                name="display_context"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            >
                                <option value="home">Home</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="footer">Footer</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="display_order"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                            >Order</label
                        >
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input
                                v-model="form.display_order"
                                type="number"
                                name="display_order"
                                id="display_order"
                                class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                            />
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
