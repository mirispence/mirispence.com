<script setup lang="ts">
import { index as artworkIndex } from '@/actions/App/Http/Controllers/Public/ArtworkController';
import { index as booksIndex } from '@/actions/App/Http/Controllers/Public/BookController';
import { create as contactCreate } from '@/actions/App/Http/Controllers/Public/ContactController';
import { index as galleriesIndex } from '@/actions/App/Http/Controllers/Public/GalleryController';
import { index as homeIndex } from '@/actions/App/Http/Controllers/Public/HomeController';
import CommissionCta from '@/components/CommissionCta.vue';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { copy } from '@/content/copy';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const page = usePage();
const seoData = computed(() => (page.props as any).seo);

// TODO(#45): replace with the shared `books` feature flag once #45 lands.
const showBooks = true;

type NavItem = { label: string; href: string; prefixes: string[] };

const navItems = computed<NavItem[]>(() =>
    [
        {
            label: 'Art',
            href: galleriesIndex.url(),
            prefixes: [galleriesIndex.url(), artworkIndex.url()],
        },
        showBooks
            ? {
                  label: 'Books',
                  href: booksIndex.url(),
                  prefixes: [booksIndex.url()],
              }
            : null,
        {
            label: 'Contact',
            href: contactCreate.url(),
            prefixes: [contactCreate.url()],
        },
    ].filter((item): item is NavItem => item !== null),
);

const matchesPrefix = (url: string, prefix: string) =>
    url === prefix ||
    url.startsWith(`${prefix}/`) ||
    url.startsWith(`${prefix}?`);

const isActive = (item: NavItem) =>
    item.prefixes.some((prefix) => matchesPrefix(page.url, prefix));

const menuOpen = ref(false);

// Close the mobile menu on any navigation, including links to the current page.
let removeStartListener: (() => void) | undefined;
onMounted(() => {
    removeStartListener = router.on('start', () => {
        menuOpen.value = false;
    });
});
onUnmounted(() => removeStartListener?.());
</script>

<template>
    <Head>
        <title>{{ seoData?.title }}</title>
        <meta
            v-if="seoData?.description"
            name="description"
            :content="seoData.description"
        />
        <link
            v-if="seoData?.canonical"
            rel="canonical"
            :href="seoData.canonical"
        />
        <meta v-if="seoData?.robots" name="robots" :content="seoData.robots" />

        <!-- Open Graph -->
        <meta
            v-if="seoData?.og?.type"
            property="og:type"
            :content="seoData.og.type"
        />
        <meta
            v-if="seoData?.og?.title"
            property="og:title"
            :content="seoData.og.title"
        />
        <meta
            v-if="seoData?.og?.description"
            property="og:description"
            :content="seoData.og.description"
        />
        <meta
            v-if="seoData?.og?.url"
            property="og:url"
            :content="seoData.og.url"
        />
        <meta
            v-if="seoData?.og?.image"
            property="og:image"
            :content="seoData.og.image"
        />
        <meta
            v-if="seoData?.og?.['image:alt']"
            property="og:image:alt"
            :content="seoData.og['image:alt']"
        />
        <meta
            v-if="seoData?.og?.site_name"
            property="og:site_name"
            :content="seoData.og.site_name"
        />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta
            v-if="seoData?.title"
            name="twitter:title"
            :content="seoData.title"
        />
        <meta
            v-if="seoData?.description"
            name="twitter:description"
            :content="seoData.description"
        />
        <meta
            v-if="seoData?.og?.image"
            name="twitter:image"
            :content="seoData.og.image"
        />

        <!-- JSON-LD -->
        <!-- eslint-disable vue/no-v-text-v-html-on-component -- :is is always the literal 'script' element -->
        <component
            :is="'script'"
            v-if="seoData?.jsonld"
            type="application/ld+json"
            v-html="JSON.stringify(seoData.jsonld)"
        >
        </component>
        <!-- eslint-enable vue/no-v-text-v-html-on-component -->
    </Head>
    <div
        class="bg-site-gradient min-h-screen font-sans text-foreground antialiased selection:bg-primary selection:text-primary-foreground"
    >
        <header class="glass-header">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-12">
                    <Link
                        :href="homeIndex.url()"
                        class="group flex items-center gap-2"
                    >
                        <span
                            class="font-display text-2xl tracking-tight text-foreground transition-all duration-300 group-hover:text-primary"
                        >
                            {{ copy.site.name }}
                        </span>
                    </Link>

                    <nav
                        aria-label="Main"
                        class="hidden items-center gap-8 md:flex"
                    >
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="
                                isActive(item)
                                    ? 'text-primary'
                                    : 'text-muted-foreground'
                            "
                            :aria-current="isActive(item) ? 'page' : undefined"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <CommissionCta
                        size="compact"
                        class="hidden sm:inline-flex"
                    />

                    <Sheet v-model:open="menuOpen">
                        <SheetTrigger
                            class="inline-flex size-10 items-center justify-center rounded-lg text-foreground transition-colors outline-none hover:bg-muted focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background md:hidden"
                            aria-label="Open menu"
                        >
                            <Menu class="size-6" aria-hidden="true" />
                        </SheetTrigger>
                        <SheetContent side="right" class="px-6 py-6">
                            <SheetHeader class="p-0">
                                <SheetTitle class="font-display text-xl">
                                    Menu
                                </SheetTitle>
                                <SheetDescription class="sr-only">
                                    Site navigation
                                </SheetDescription>
                            </SheetHeader>
                            <nav aria-label="Main" class="flex flex-col gap-1">
                                <Link
                                    v-for="item in navItems"
                                    :key="item.label"
                                    :href="item.href"
                                    class="rounded-lg px-3 py-3 text-base font-medium transition-colors hover:bg-muted hover:text-primary"
                                    :class="
                                        isActive(item)
                                            ? 'text-primary'
                                            : 'text-foreground'
                                    "
                                    :aria-current="
                                        isActive(item) ? 'page' : undefined
                                    "
                                >
                                    {{ item.label }}
                                </Link>
                            </nav>
                            <CommissionCta class="mt-4 w-full" />
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>

        <main class="relative">
            <slot />
        </main>

        <footer class="mt-20 border-t border-border bg-card py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col items-center justify-between gap-6 md:flex-row"
                >
                    <p class="font-display text-lg text-foreground">
                        {{ copy.site.name }}
                    </p>
                    <div class="flex items-center gap-6">
                        <a
                            href="https://bsky.app/profile/mirispence.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow me on Bluesky"
                            class="group rounded-full bg-muted p-2.5 transition-all hover:bg-primary/10"
                        >
                            <img
                                src="/build/icons/bluesky.svg"
                                alt=""
                                class="h-5 w-5 transition-all group-hover:scale-110"
                            />
                        </a>
                        <a
                            href="https://cara.app/mirialexandria"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow me on Cara"
                            class="group rounded-full bg-muted p-2.5 transition-all hover:bg-primary/10"
                        >
                            <img
                                src="/build/icons/cara.svg"
                                alt=""
                                class="h-5 w-5 transition-all group-hover:scale-110"
                            />
                        </a>
                        <a
                            href="https://www.instagram.com/mirialexandria/"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow me on Instagram"
                            class="group rounded-full bg-muted p-2.5 transition-all hover:bg-primary/10"
                        >
                            <img
                                src="/build/icons/instagram.svg"
                                alt=""
                                class="h-5 w-5 transition-all group-hover:scale-110"
                            />
                        </a>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }}
                        {{ copy.footer.copyright }}
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
