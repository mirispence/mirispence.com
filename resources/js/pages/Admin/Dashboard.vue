<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            </div>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                <div class="py-4">
                    <div
                        class="h-96 rounded-lg border-4 border-dashed border-gray-200 p-4"
                    >
                        <p class="text-center text-gray-500">
                            Welcome to the Admin Dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
