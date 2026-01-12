<script setup lang="ts">
import {
    create,
    destroy,
    edit,
} from '@/actions/App/Http/Controllers/Admin/FeaturedItemController';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps<{
    featuredItems: {
        data: Array<any>;
        links: Array<any>;
    };
}>();

const deleteItem = (id: number) => {
    if (confirm('Are you sure you want to delete this featured item?')) {
        router.delete(destroy.url(id));
    }
};

const getItemName = (item: any) => {
    if (!item.item) return 'Unknown Item';
    return item.item.title || item.item.name || 'Unknown';
};

const getItemType = (type: string) => {
    if (type.includes('Artwork')) return 'Artwork';
    if (type.includes('Book')) return 'Book';
    return type;
};
</script>

<template>
    <Head title="Featured Items" />

    <AdminLayout>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">
                    Featured Items
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Manage items featured on the home page and other areas.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <Link
                    :href="create.url()"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Add Featured Item
                </Link>
            </div>
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
                                    Item
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Type
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Context
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Order
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
                                v-for="item in featuredItems.data"
                                :key="item.id"
                            >
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                                >
                                    {{ getItemName(item) }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ getItemType(item.item_type) }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 capitalize"
                                >
                                    {{ item.display_context }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ item.display_order }}
                                </td>
                                <td
                                    class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                                >
                                    <Link
                                        :href="edit.url(item.id)"
                                        class="mr-4 text-indigo-600 hover:text-indigo-900"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="deleteItem(item.id)"
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

        <Pagination :links="featuredItems.links" />
    </AdminLayout>
</template>
