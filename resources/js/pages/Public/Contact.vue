<script setup lang="ts">
import FormSelect from '@/components/FormSelect.vue';
import FormTextarea from '@/components/FormTextarea.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    success?: string;
}>();

const urlParams = new URLSearchParams(window.location.search);
const initialType =
    urlParams.get('type') === 'commission' ? 'commission' : 'general';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    type: initialType,
    budget_range: '',
    desired_due_date: '',
    honeypot: '',
});

const submit = () => {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl">
                <div class="mb-16 text-center">
                    <h1
                        class="font-heading text-5xl font-black tracking-tight text-foreground"
                    >
                        Get in Touch
                    </h1>
                    <p class="mt-4 text-xl text-muted-foreground">
                        Have a question or want to work together? I'd love to
                        hear from you.
                    </p>
                </div>

                <div
                    v-if="$page.props.flash.success"
                    class="mb-10 animate-in rounded-2xl border border-green-100 bg-green-50 p-6 shadow-sm duration-500 fade-in slide-in-from-top-4"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="flex-shrink-0 rounded-full bg-green-500 p-1"
                        >
                            <svg
                                class="h-4 w-4 text-white"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-green-800">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-[2.5rem] border border-white/60 bg-white/40 p-8 shadow-premium backdrop-blur-xl sm:p-12"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Honeypot -->
                        <div class="hidden">
                            <label for="honeypot">Don't fill this out</label>
                            <input
                                type="text"
                                id="honeypot"
                                v-model="form.honeypot"
                            />
                        </div>

                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label
                                    for="type"
                                    class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                    >Message Type</label
                                >
                                <FormSelect id="type" v-model="form.type">
                                    <option value="general">
                                        General Inquiry
                                    </option>
                                    <option value="commission">
                                        Commission Request
                                    </option>
                                </FormSelect>
                            </div>

                            <div class="sm:col-span-1">
                                <label
                                    for="name"
                                    class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                    >Name</label
                                >
                                <Input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Your name"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <label
                                    for="email"
                                    class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                    >Email</label
                                >
                                <Input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    placeholder="email@example.com"
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label
                                    for="subject"
                                    class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                    >Subject</label
                                >
                                <Input
                                    type="text"
                                    id="subject"
                                    v-model="form.subject"
                                    placeholder="What is this regarding?"
                                />
                                <div
                                    v-if="form.errors.subject"
                                    class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                >
                                    {{ form.errors.subject }}
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label
                                    for="message"
                                    class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                    >Message</label
                                >
                                <FormTextarea
                                    id="message"
                                    v-model="form.message"
                                    placeholder="Tell me more..."
                                />
                                <div
                                    v-if="form.errors.message"
                                    class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                >
                                    {{ form.errors.message }}
                                </div>
                            </div>

                            <template v-if="form.type === 'commission'">
                                <div class="sm:col-span-1">
                                    <label
                                        for="budget_range"
                                        class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                        >Budget Range</label
                                    >
                                    <Input
                                        type="text"
                                        id="budget_range"
                                        v-model="form.budget_range"
                                        placeholder="e.g. $100-$300"
                                    />
                                    <div
                                        v-if="form.errors.budget_range"
                                        class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                    >
                                        {{ form.errors.budget_range }}
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label
                                        for="desired_due_date"
                                        class="mb-3 ml-1 block text-xs font-black tracking-[0.2em] text-muted-foreground uppercase"
                                        >Desired Due Date</label
                                    >
                                    <Input
                                        type="date"
                                        id="desired_due_date"
                                        v-model="form.desired_due_date"
                                    />
                                    <div
                                        v-if="form.errors.desired_due_date"
                                        class="mt-2 ml-1 text-xs font-bold tracking-wider text-accent uppercase"
                                    >
                                        {{ form.errors.desired_due_date }}
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-4">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="h-14 w-full rounded-full bg-primary text-lg font-bold shadow-premium"
                            >
                                {{
                                    form.processing
                                        ? 'Sending message...'
                                        : 'Send Message'
                                }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
