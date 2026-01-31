<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    book: any;
}>();
</script>

<template>
    <Head :title="book.title" />

    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-16">
                <!-- Cover -->
                <div class="flex justify-center lg:sticky lg:top-32">
                    <Card
                        class="w-full max-w-[450px] overflow-hidden rounded-[2rem] border-none p-0 shadow-2xl"
                    >
                        <div class="aspect-[2/3] bg-panel">
                            <img
                                v-if="book.image_url"
                                :src="book.image_url"
                                :alt="book.title"
                                class="h-full w-full object-cover"
                            />
                            <!-- Placeholder -->
                            <div
                                v-else
                                class="flex h-full items-center justify-center font-heading text-2xl text-muted-foreground italic"
                            >
                                No Cover
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Info -->
                <div class="mt-16 px-4 sm:px-0 lg:mt-0">
                    <div class="mb-8 flex flex-col gap-4">
                        <h1
                            class="font-heading text-5xl leading-tight font-black tracking-tight text-foreground"
                        >
                            {{ book.title }}
                        </h1>
                        <div
                            v-if="book.release_date"
                            class="flex items-center gap-2"
                        >
                            <span
                                class="text-xs font-black tracking-[0.2em] text-accent uppercase"
                                >Released</span
                            >
                            <span
                                class="text-sm font-bold text-muted-foreground"
                                >{{ book.release_date }}</span
                            >
                        </div>
                    </div>

                    <div
                        class="prose prose-lg prose-slate mb-12 max-w-none leading-relaxed text-muted-foreground"
                    >
                        <div v-html="book.description_html" />
                    </div>

                    <div class="space-y-12">
                        <!-- Chapters -->
                        <div v-if="book.chapters && book.chapters.length">
                            <h2
                                class="mb-6 font-heading text-2xl font-bold text-foreground"
                            >
                                Chapters
                            </h2>
                            <div
                                class="divide-y divide-border/40 overflow-hidden rounded-3xl border border-white/60 bg-white/40 backdrop-blur-xl"
                            >
                                <div
                                    v-for="chapter in book.chapters"
                                    :key="chapter.id"
                                    class="flex items-center justify-between p-6 transition-colors hover:bg-white/60"
                                >
                                    <div class="flex items-center gap-4">
                                        <p class="font-bold text-foreground">
                                            {{ chapter.title }}
                                        </p>
                                        <span
                                            v-if="chapter.is_sample"
                                            class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-0.5 text-[10px] font-black tracking-widest text-primary uppercase"
                                            >Sample</span
                                        >
                                    </div>
                                    <!-- Read Button if link exists -->
                                </div>
                            </div>
                        </div>

                        <!-- External Links -->
                        <div v-if="book.external_links">
                            <h3
                                class="mb-6 font-heading text-xl font-bold text-foreground"
                            >
                                Available at
                            </h3>
                            <div class="flex flex-wrap gap-4">
                                <a
                                    v-for="(url, label) in book.external_links"
                                    :key="label"
                                    :href="url"
                                    target="_blank"
                                >
                                    <Button
                                        variant="outline"
                                        class="group h-12 rounded-full px-8 font-bold"
                                    >
                                        {{ label }}
                                        <svg
                                            class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </Button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 border-t border-border/60 pt-12">
                        <Link href="/books">
                            <Button
                                variant="ghost"
                                class="rounded-full text-muted-foreground hover:text-primary"
                            >
                                <svg
                                    class="mr-2 h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                    />
                                </svg>
                                Back to Library
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
