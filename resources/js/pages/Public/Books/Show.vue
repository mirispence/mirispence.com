<script setup lang="ts">
import { index as booksIndex } from '@/actions/App/Http/Controllers/Public/BookController';
import { show as galleryShow } from '@/actions/App/Http/Controllers/Public/GalleryController';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { copy } from '@/content/copy';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface ResponsiveImage {
    src: string;
    srcset: string;
}

interface BookCover {
    urls: {
        thumb?: string;
        grid?: ResponsiveImage;
        display?: ResponsiveImage;
    };
    gallery: {
        name: string;
        slug: string;
    };
}

defineProps<{
    book: Record<string, any> & { cover: BookCover | null };
}>();
</script>

<template>
    <PublicLayout>
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-16">
                <!-- Cover -->
                <div
                    v-if="book.cover?.urls?.display?.src"
                    class="flex justify-center lg:sticky lg:top-32"
                >
                    <Link
                        :href="galleryShow.url(book.cover.gallery.slug)"
                        class="block w-full max-w-[450px]"
                        :aria-label="`View the ${book.cover.gallery.name} gallery`"
                    >
                        <Card
                            class="w-full overflow-hidden rounded-sm border-none p-0 shadow-none hover:translate-y-0 hover:shadow-none"
                        >
                            <div class="aspect-[2/3] bg-muted">
                                <img
                                    :src="book.cover.urls.display.src"
                                    :srcset="book.cover.urls.display.srcset"
                                    sizes="(min-width: 1024px) 450px, 100vw"
                                    :alt="book.title"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                        </Card>
                    </Link>
                </div>

                <!-- Info -->
                <div class="mt-16 px-4 sm:px-0 lg:mt-0">
                    <div class="mb-8 flex flex-col gap-4">
                        <h1
                            class="font-display text-4xl leading-tight tracking-tight wrap-break-word text-foreground sm:text-5xl"
                        >
                            {{ book.title }}
                        </h1>
                        <div
                            v-if="book.release_date"
                            class="flex items-center gap-2"
                        >
                            <span class="text-sm font-semibold text-primary"
                                >Released</span
                            >
                            <span
                                class="text-sm font-semibold text-muted-foreground"
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
                                class="mb-6 font-display text-2xl text-foreground"
                            >
                                {{ copy.book.chaptersHeading }}
                            </h2>
                            <div
                                class="divide-y divide-border overflow-hidden rounded-lg border border-border bg-card shadow-card"
                            >
                                <div
                                    v-for="chapter in book.chapters"
                                    :key="chapter.id"
                                    class="flex flex-col gap-2 p-6 transition-colors hover:bg-muted"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center gap-4">
                                            <p
                                                class="font-semibold text-foreground"
                                            >
                                                {{ chapter.title }}
                                            </p>
                                            <span
                                                v-if="chapter.is_sample"
                                                class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-0.5 text-xs font-semibold text-primary"
                                                >Sample</span
                                            >
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            chapter.is_sample &&
                                            chapter.body_html
                                        "
                                        class="prose prose-sm dark:prose-invert mt-4 max-w-none"
                                        v-html="chapter.body_html"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- External Links -->
                        <div v-if="book.external_links">
                            <h3
                                class="mb-6 text-xl font-semibold text-foreground"
                            >
                                {{ copy.book.availableAtHeading }}
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
                                        class="group h-12 px-8 font-semibold"
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

                    <div class="mt-16 border-t border-border pt-12">
                        <Link :href="booksIndex.url()">
                            <Button
                                variant="ghost"
                                class="text-muted-foreground hover:text-primary"
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
