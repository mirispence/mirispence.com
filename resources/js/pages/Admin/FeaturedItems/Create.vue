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
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Create Featured Item</h2>
            </div>

            <form @submit.prevent="submit" class="mt-8">
                <div class="admin-card">
                    <div class="admin-card-body form-stack">
                        <div class="form-row">
                            <label for="item_type" class="form-label">Item Type</label>
                            <div class="form-field">
                                <select
                                    v-model="form.item_type"
                                    id="item_type"
                                    class="control control-select"
                                >
                                    <option value="artwork">Artwork</option>
                                    <option value="book">Book</option>
                                </select>
                                <div v-if="form.errors.item_type" class="error">
                                    {{ form.errors.item_type }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="item_id" class="form-label">Item</label>
                            <div class="form-field">
                                <select
                                    v-model="form.item_id"
                                    id="item_id"
                                    class="control control-select"
                                >
                                    <option value="" disabled>Select an item</option>
                                    <option
                                        v-for="item in items"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                        {{ item.title }}
                                    </option>
                                </select>
                                <div v-if="form.errors.item_id" class="error">
                                    {{ form.errors.item_id }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="display_context" class="form-label">Context</label>
                            <div class="form-field">
                                <select
                                    v-model="form.display_context"
                                    id="display_context"
                                    class="control control-select"
                                >
                                    <option value="home">Home</option>
                                    <option value="sidebar">Sidebar</option>
                                    <option value="footer">Footer</option>
                                </select>
                                <div v-if="form.errors.display_context" class="error">
                                    {{ form.errors.display_context }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="display_order" class="form-label">Order</label>
                            <div class="form-field">
                                <input
                                    v-model="form.display_order"
                                    type="number"
                                    id="display_order"
                                    class="control"
                                />
                                <div v-if="form.errors.display_order" class="error">
                                    {{ form.errors.display_order }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <div class="actions-bar-inner">
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
