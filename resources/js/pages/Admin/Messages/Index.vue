<script setup lang="ts">
import {
    destroy,
    show,
} from '@/actions/App/Http/Controllers/Admin/MessageController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps<{
    messages: {
        data: Array<any>;
        links: Array<any>;
    };
}>();

const deleteMessage = (id: number) => {
    if (confirm('Are you sure you want to delete this message?')) {
        router.delete(destroy.url(id));
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString();
};
</script>

<template>
    <Head title="Messages" />

    <AdminLayout>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">
                    Messages & Commissions
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    View and manage contact messages and commission requests.
                </p>
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
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Subject
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
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Date
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pr-4 pl-3 sm:pr-0"
                                >
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="message in messages.data"
                                :key="message.id"
                            >
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                                >
                                    {{ message.name }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ message.subject }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 capitalize"
                                >
                                    {{ message.type }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 capitalize"
                                >
                                    {{ message.status }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ formatDate(message.created_at) }}
                                </td>
                                <td
                                    class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                                >
                                    <Link
                                        :href="show.url(message.id)"
                                        class="mr-4 text-indigo-600 hover:text-indigo-900"
                                        >View</Link
                                    >
                                    <button
                                        @click="deleteMessage(message.id)"
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
    </AdminLayout>
</template>
