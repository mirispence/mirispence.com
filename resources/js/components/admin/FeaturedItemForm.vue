<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3';
import { computed } from 'vue';

export interface FeaturedItemFormData {
    item_type: string;
    item_id: string | number;
    display_context: string;
    display_order: number;
    _method?: string;
}

const props = defineProps<{
    form: InertiaForm<FeaturedItemFormData>;
    artworks: Array<any>;
    books: Array<any>;
}>();
defineEmits<{ submit: [] }>();

const items = computed(() => {
    if (props.form.item_type === 'artwork') {
        return props.artworks;
    } else if (props.form.item_type === 'book') {
        return props.books;
    }
    return [];
});
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="mt-8">
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
                    <label for="display_context" class="form-label"
                        >Context</label
                    >
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
</template>
