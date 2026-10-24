<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3';

export interface GalleryFormData {
    name: string;
    description: string | null;
    sort_order: number;
    publish_status: string;
    cover_artwork_id?: number | null;
    _method?: string;
}

defineProps<{ form: InertiaForm<GalleryFormData> }>();
defineEmits<{ submit: [] }>();
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="mt-8">
        <div class="admin-card">
            <div class="admin-card-body form-stack">
                <div class="form-row">
                    <label for="name" class="form-label">Name</label>
                    <div class="form-field">
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="control"
                        />
                        <div v-if="form.errors.name" class="error">
                            {{ form.errors.name }}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <label for="description" class="form-label"
                        >Description</label
                    >
                    <div class="form-field">
                        <textarea
                            v-model="form.description"
                            id="description"
                            rows="3"
                            class="control control-textarea"
                        ></textarea>
                        <div v-if="form.errors.description" class="error">
                            {{ form.errors.description }}
                        </div>
                    </div>
                </div>

                <slot name="cover">
                    <div class="form-row">
                        <span class="form-label">Cover</span>
                        <div class="form-field">
                            <p class="text-sm text-gray-500">
                                The cover is chosen from this gallery's
                                artworks. Add artworks to the gallery, then pick
                                a cover when editing it.
                            </p>
                        </div>
                    </div>
                </slot>

                <div class="form-row">
                    <label for="sort_order" class="form-label"
                        >Sort Order</label
                    >
                    <div class="form-field">
                        <input
                            v-model="form.sort_order"
                            type="number"
                            id="sort_order"
                            class="control"
                        />
                        <div v-if="form.errors.sort_order" class="error">
                            {{ form.errors.sort_order }}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <label for="publish_status" class="form-label"
                        >Status</label
                    >
                    <div class="form-field">
                        <select
                            v-model="form.publish_status"
                            id="publish_status"
                            class="control control-select"
                        >
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
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
