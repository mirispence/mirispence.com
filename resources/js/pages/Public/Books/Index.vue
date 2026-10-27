<script setup lang="ts">
import { show as bookShow } from '@/actions/App/Http/Controllers/Public/BookController';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { copy } from '@/content/copy';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    books: Array<any>;
}>();
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h1
                    class="font-display text-4xl tracking-tight wrap-break-word text-foreground sm:text-5xl"
                >
                    {{ copy.books.heading }}
                </h1>
                <p class="mt-4 text-xl text-muted-foreground">
                    {{ copy.books.intro }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="book in books"
                    :key="book.id"
                    class="group flex flex-col overflow-hidden border-none bg-card p-0"
                >
                    <div class="relative aspect-[2/3] overflow-hidden bg-muted">
                        <img
                            v-if="book.cover?.urls?.thumb"
                            :src="book.cover.urls.thumb"
                            :alt="book.title"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <!-- Placeholder for cover -->
                        <div
                            v-else
                            class="flex h-full items-center justify-center text-muted-foreground italic"
                        >
                            No Cover available
                        </div>
                        <div
                            class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100 focus-within:opacity-100"
                        >
                            <Link
                                :href="bookShow.url(book.slug)"
                                class="w-full"
                            >
                                <Button class="w-full">Read More</Button>
                            </Link>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-8">
                        <div class="mb-4 flex flex-col gap-2">
                            <h3
                                class="text-2xl font-semibold text-foreground transition-colors group-hover:text-primary"
                            >
                                <Link :href="bookShow.url(book.slug)">
                                    {{ book.title }}
                                </Link>
                            </h3>
                            <p
                                class="text-sm font-semibold text-primary"
                                v-if="book.release_date"
                            >
                                {{ book.release_date }}
                            </p>
                        </div>
                        <p
                            class="line-clamp-3 leading-relaxed text-muted-foreground"
                            v-if="book.description"
                        >
                            {{
                                book.description
                                    .replace(/<[^>]*>?/gm, '')
                                    .substring(0, 150)
                            }}...
                        </p>
                    </div>
                </Card>

                <!-- Coming Soon Card -->
                <Card
                    class="group flex cursor-default flex-col items-center justify-center overflow-hidden border-2 border-dashed border-border bg-transparent p-12 text-center shadow-none hover:translate-y-0 hover:shadow-none"
                >
                    <div
                        class="mb-6 rounded-full bg-muted p-6 transition-transform duration-500 group-hover:scale-110"
                    >
                        <svg
                            aria-hidden="true"
                            class="h-10 w-10 text-muted-foreground"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-muted-foreground">
                        {{ copy.books.comingSoonHeading }}
                    </h3>
                    <p class="mt-3 max-w-[200px] text-muted-foreground">
                        {{ copy.books.comingSoonBody }}
                    </p>
                </Card>
            </div>
        </div>
    </PublicLayout>
</template>
