<script setup lang="ts">
import {
    create,
    destroy,
    edit,
    regenerate as regenerateAction,
    bulkRegenerate as bulkRegenerateAction,
} from '@/actions/App/Http/Controllers/Admin/ArtworkController';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    artworks: {
        data: Array<any>;
        links: Array<any>;
    };
}>();

const deleteArtwork = (id: number) => {
    if (confirm('Are you sure you want to delete this artwork?')) {
        router.delete(destroy.url(id));
    }
};

const selectedArtworks = ref<number[]>([]);

const toggleSelection = (id: number) => {
    if (selectedArtworks.value.includes(id)) {
        selectedArtworks.value = selectedArtworks.value.filter(i => i !== id);
    } else {
        selectedArtworks.value.push(id);
    }
};

const bulkRegenerate = () => {
    if (confirm(`Regenerate images for ${selectedArtworks.value.length} artworks?`)) {
        router.post(bulkRegenerateAction.url(), {
            ids: selectedArtworks.value
        }, {
            onSuccess: () => selectedArtworks.value = []
        });
    }
};

const regenerate = (id: number) => {
    router.post(regenerateAction.url(id));
};
</script>

<template>
    <Head title="Artworks" />

    <AdminLayout>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Artworks</h1>
                <p class="mt-2 text-sm text-gray-700">
                    A list of all artworks in your portfolio.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex sm:gap-3 sm:flex-none">
                <button
                    v-if="selectedArtworks.length > 0"
                    @click="bulkRegenerate"
                    class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                    Regenerate Selected ({{ selectedArtworks.length }})
                </button>
                <Link
                    :href="create.url()"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Add Artwork
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
                                    <input
                                        type="checkbox"
                                        @change="selectedArtworks = artworks.data.map(a => a.id)"
                                        :checked="selectedArtworks.length === artworks.data.length && artworks.data.length > 0"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                >
                                    Thumbnail
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Title
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Visibility
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Image Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Created On
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
                                v-for="artwork in artworks.data"
                                :key="artwork.id"
                            >
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="selectedArtworks.includes(artwork.id)"
                                        @change="toggleSelection(artwork.id)"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    />
                                </td>
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                                >
                                    <img
                                        v-if="artwork.image_url"
                                        :src="artwork.image_url"
                                        class="h-10 w-10 rounded-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-xs text-gray-500"
                                    >
                                        No Img
                                    </div>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm font-medium whitespace-nowrap text-gray-900"
                                >
                                    {{ artwork.title }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    <span
                                        :class="[
                                            artwork.publish_status === 'published' ? 'text-green-600' :
                                            artwork.publish_status === 'draft' ? 'text-blue-600' :
                                            artwork.publish_status === 'archived' ? 'text-red-600' :
                                            'text-gray-400',
                                            'inline-flex items-center text-xs font-semibold'
                                        ]"
                                    >
                                        {{ artwork.publish_status_label }}
                                    </span>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    <span
                                        :class="[
                                            artwork.image_status === 'ready' ? 'text-green-600' :
                                            artwork.image_status === 'processing' ? 'text-blue-600' :
                                            artwork.image_status === 'failed' ? 'text-red-600' :
                                            'text-gray-400',
                                            'inline-flex items-center text-xs font-semibold'
                                        ]"
                                    >
                                        {{ artwork.image_status_label }}
                                    </span>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                                >
                                    {{ artwork.created_on }}
                                </td>
                                <td
                                    class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                                >
                                    <Link
                                        :href="edit.url(artwork.id)"
                                        class="mr-4 text-indigo-600 hover:text-indigo-900"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="regenerate(artwork.id)"
                                        class="mr-4 text-indigo-600 hover:text-indigo-900"
                                    >
                                        Regenerate
                                    </button>
                                    <button
                                        @click="deleteArtwork(artwork.id)"
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

        <Pagination :links="artworks.links" />
    </AdminLayout>
</template>
