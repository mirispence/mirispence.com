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
        <div class="admin-page">
            <div class="admin-header">
                <h2 class="admin-title">Message Details</h2>
            </div>

            <div class="admin-card mt-8">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Information</h3>
                    <p class="admin-card-description">Details about the message or commission request.</p>
                </div>
                <div class="admin-card-body form-stack">
                    <div class="form-row">
                        <dt class="form-label">Full name</dt>
                        <dd class="form-field text-gray-900">
                            {{ message.name }}
                        </dd>
                    </div>

                    <div class="form-row">
                        <dt class="form-label">Email address</dt>
                        <dd class="form-field text-gray-900">
                            {{ message.email }}
                        </dd>
                    </div>

                    <div class="form-row">
                        <dt class="form-label">Subject</dt>
                        <dd class="form-field text-gray-900">
                            {{ message.subject }}
                        </dd>
                    </div>

                    <div class="form-row">
                        <dt class="form-label">Type</dt>
                        <dd class="form-field text-gray-900 capitalize">
                            {{ message.type }}
                        </dd>
                    </div>

                    <div class="form-row">
                        <dt class="form-label">Date</dt>
                        <dd class="form-field text-gray-900">
                            {{ formatDate(message.created_at) }}
                        </dd>
                    </div>

                    <div class="form-row">
                        <dt class="form-label">Message</dt>
                        <dd class="form-field text-gray-900 whitespace-pre-wrap">
                            {{ message.message }}
                        </dd>
                    </div>

                    <template v-if="message.commission_request">
                        <div class="form-row bg-gray-50/50 -mx-6 px-6 py-4">
                            <h4 class="text-sm font-bold text-gray-900">
                                Commission Request Details
                            </h4>
                        </div>

                        <div v-if="message.commission_request.project_description" class="form-row">
                            <dt class="form-label">Project Description</dt>
                            <dd class="form-field text-gray-900 whitespace-pre-wrap">
                                {{ message.commission_request.project_description }}
                            </dd>
                        </div>

                        <div v-if="message.commission_request.budget_range" class="form-row">
                            <dt class="form-label">Budget Range</dt>
                            <dd class="form-field text-gray-900">
                                {{ message.commission_request.budget_range }}
                            </dd>
                        </div>

                        <div v-if="message.commission_request.desired_due_date" class="form-row">
                            <dt class="form-label">Desired Due Date</dt>
                            <dd class="form-field text-gray-900">
                                {{ message.commission_request.desired_due_date }}
                            </dd>
                        </div>

                        <div class="form-row">
                            <dt class="form-label">Commission Status</dt>
                            <dd class="form-field">
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

                        <div v-if="message.commission_request.quote_amount" class="form-row">
                            <dt class="form-label">Quote Amount</dt>
                            <dd class="form-field text-gray-900">
                                ${{ message.commission_request.quote_amount }}
                            </dd>
                        </div>

                        <div v-if="message.commission_request.invoice_link" class="form-row">
                            <dt class="form-label">Invoice Link</dt>
                            <dd class="form-field">
                                <a :href="message.commission_request.invoice_link" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    View Invoice
                                </a>
                            </dd>
                        </div>
                    </template>

                    <div class="form-row">
                        <dt class="form-label">Status</dt>
                        <dd class="form-field">
                            <select
                                v-model="form.status"
                                @change="updateStatus"
                                class="control control-select max-w-xs"
                            >
                                <option value="new">New</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                                <option value="archived">Archived</option>
                            </select>
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
