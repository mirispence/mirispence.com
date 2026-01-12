<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="bg-site-gradient min-h-screen font-sans text-foreground antialiased selection:bg-accent/20 selection:text-accent"
    >
        <header class="glass-header">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-12">
                    <Link href="/" class="group flex items-center gap-2">
                        <span
                            class="font-heading text-2xl font-black tracking-tight text-foreground transition-all duration-300 group-hover:text-primary"
                        >
                            Miri Spence
                        </span>
                    </Link>

                    <nav class="hidden items-center gap-8 md:flex">
                        <Link
                            href="/art"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="
                                $page.url.startsWith('/art')
                                    ? 'text-primary'
                                    : 'text-muted-foreground'
                            "
                        >
                            Art
                        </Link>
                        <Link
                            href="/books"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="
                                $page.url.startsWith('/books')
                                    ? 'text-primary'
                                    : 'text-muted-foreground'
                            "
                        >
                            Books
                        </Link>
                        <Link
                            href="/contact"
                            class="text-sm font-medium transition-colors hover:text-primary"
                            :class="
                                $page.url.startsWith('/contact')
                                    ? 'text-primary'
                                    : 'text-muted-foreground'
                            "
                        >
                            Contact
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <Link
                        href="/contact?type=commission"
                        class="hidden items-center justify-center rounded-full bg-accent px-4 py-1.5 text-xs font-bold tracking-widest text-white uppercase transition-all hover:scale-105 hover:bg-accent/90 active:scale-95 sm:inline-flex"
                    >
                        Hire Me
                    </Link>
                </div>
            </div>
        </header>

        <main class="relative">
            <slot />
        </main>

        <footer
            class="mt-20 border-t border-border bg-white/50 py-12 backdrop-blur-sm"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col items-center justify-between gap-6 md:flex-row"
                >
                    <p class="font-heading text-lg font-bold text-foreground">
                        Miri Spence
                    </p>
                    <p class="text-sm text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }} Miri Spence. All
                        rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
