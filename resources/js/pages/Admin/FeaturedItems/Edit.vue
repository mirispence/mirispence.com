<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/FeaturedItemController';
import FeaturedItemForm, {
    type FeaturedItemFormData,
} from '@/components/admin/FeaturedItemForm.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    featuredItem: any;
    artworks: Array<any>;
    books: Array<any>;
}>();

const form = useForm<FeaturedItemFormData>({
    _method: 'PUT',
    item_type: props.featuredItem.item_type,
    item_id: props.featuredItem.item_id,
    display_context: props.featuredItem.display_context,
    display_order: props.featuredItem.display_order,
});

const submit = () => {
    form.post(update.url(props.featuredItem.id));
};
</script>

<template>
    <Head title="Edit Featured Item" />

    <AdminLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Edit Featured Item</h2>
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
