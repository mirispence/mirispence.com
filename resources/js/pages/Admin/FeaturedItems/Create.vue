<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Admin/FeaturedItemController';
import FeaturedItemForm, {
    type FeaturedItemFormData,
} from '@/components/admin/FeaturedItemForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    artworks: Array<any>;
    books: Array<any>;
}>();

const form = useForm<FeaturedItemFormData>({
    item_type: 'artwork',
    item_id: '',
    display_context: 'home',
    display_order: 1,
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Featured Item" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Featured Item</h2>
            </div>

            <FeaturedItemForm
                :form="form"
                :artworks="artworks"
                :books="books"
                @submit="submit"
            />
        </div>
    </AdminLayout>
</template>
