<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Admin/MessageController';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    message: any;
}>();

const form = useForm({
    status: props.message.status,
});

const updateStatus = () => {
    form.put(update.url(props.message.id));
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleString();
};
</script>

<template>
    <Head title="View Message" />

    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl leading-7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                >
                    Message Details
                </h2>
            </div>
        </div>

        <div class="mt-8 overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Information
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Details about the message or commission request.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Full name
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ message.name }}
                        </dd>
                    </div>
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Email address
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ message.email }}
                        </dd>
                    </div>
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Subject
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ message.subject }}
                        </dd>
                    </div>
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 capitalize sm:col-span-2 sm:mt-0"
                        >
                            {{ message.type }}
                        </dd>
                    </div>
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ formatDate(message.created_at) }}
                        </dd>
                    </div>
                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Message
                        </dt>
                        <dd
                            class="mt-1 text-sm whitespace-pre-wrap text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ message.message }}
                        </dd>
                    </div>

                    <template v-if="message.commission_request">
                        <div
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 bg-gray-50/50"
                        >
                            <dt class="text-sm font-bold text-gray-900 border-b border-gray-200 pb-2 sm:col-span-3 sm:mb-2 sm:border-b-0 sm:pb-0">
                                Commission Request Details
                            </dt>
                        </div>
                        <div
                            v-if="message.commission_request.project_description"
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Project Description
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap sm:col-span-2 sm:mt-0">
                                {{ message.commission_request.project_description }}
                            </dd>
                        </div>
                        <div
                            v-if="message.commission_request.budget_range"
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Budget Range
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ message.commission_request.budget_range }}
                            </dd>
                        </div>
                        <div
                            v-if="message.commission_request.desired_due_date"
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Desired Due Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ message.commission_request.desired_due_date }}
                            </dd>
                        </div>
                        <div
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Commission Status
                            </dt>
                            <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                <span :class="[
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
                                    message.commission_request.status === 'completed' ? 'bg-green-100 text-green-800' :
                                    message.commission_request.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                    'bg-blue-100 text-blue-800'
                                ]">
                                    {{ message.commission_request.status }}
                                </span>
                            </dd>
                        </div>
                        <div
                            v-if="message.commission_request.quote_amount"
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Quote Amount
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                ${{ message.commission_request.quote_amount }}
                            </dd>
                        </div>
                        <div
                            v-if="message.commission_request.invoice_link"
                            class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <dt class="text-sm font-medium text-gray-500">
                                Invoice Link
                            </dt>
                            <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                <a :href="message.commission_request.invoice_link" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    View Invoice
                                </a>
                            </dd>
                        </div>
                    </template>

                    <div
                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Status
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            <select
                                v-model="form.status"
                                @change="updateStatus"
                                class="mt-1 block w-full rounded-md border-gray-300 py-2 pr-10 pl-3 text-base focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none sm:text-sm"
                            >
                                <option value="new">New</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                                <option value="archived">Archived</option>
                            </select>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </AdminLayout>
</template>
