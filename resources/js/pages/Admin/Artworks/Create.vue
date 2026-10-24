<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/ArtworkController';
import ArtworkForm, {
    type ArtworkFormData,
} from '@/components/admin/ArtworkForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    galleries: Array<any>;
    tags: Array<any>;
}>();

const form = useForm<ArtworkFormData>({
    title: '',
    description: '',
    alt_text: '',
    created_on: '',
    publish_status: 'published',
    nsfw_flag: false,
    featured_flag: false,
    galleries: [],
    tags: [],
    image: null,
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Artwork" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Artwork</h2>
            </div>

            <ArtworkForm
                :form="form"
                :galleries="galleries"
                :tags="tags"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>
