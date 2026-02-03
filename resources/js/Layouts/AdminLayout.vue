<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const navigation = [
    { name: 'Dashboard', href: '/admin', icon: 'HomeIcon' },
    { name: 'Artworks', href: '/admin/artworks', icon: 'PhotoIcon' },
    { name: 'Galleries', href: '/admin/galleries', icon: 'RectangleStackIcon' },
    { name: 'Books', href: '/admin/books', icon: 'BookOpenIcon' },
    { name: 'Tags', href: '/admin/tags', icon: 'TagIcon' },
    { name: 'Featured', href: '/admin/featured-items', icon: 'StarIcon' },
    { name: 'Messages', href: '/admin/messages', icon: 'EnvelopeIcon' },
    { name: 'Users', href: '/admin/users', icon: 'UsersIcon' },
];
</script>

<template>
    <Head>
        <meta name="robots" content="noindex, nofollow" />
    </Head>
    <div class="min-h-full">
        <div class="flex h-screen overflow-hidden bg-gray-100">
            <!-- Sidebar -->
            <div class="hidden md:flex md:flex-shrink-0">
                <div class="flex w-64 flex-col">
                    <div
                        class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white"
                    >
                        <div
                            class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4"
                        >
                            <div class="flex flex-shrink-0 items-center px-4">
                                <span class="text-xl font-bold text-gray-900"
                                    >Admin</span
                                >
                            </div>
                            <nav class="mt-5 flex-1 space-y-1 bg-white px-2">
                                <Link
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :class="[
                                        $page.url.startsWith(item.href)
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                        'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                                    ]"
                                >
                                    {{ item.name }}
                                </Link>
                            </nav>
                        </div>
                        <div
                            class="flex flex-shrink-0 border-t border-gray-200 p-4"
                        >
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="group block w-full flex-shrink-0"
                            >
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p
                                            class="text-sm font-medium text-gray-700 group-hover:text-gray-900"
                                        >
                                            Logout
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-1 flex-col overflow-hidden">
                <main class="flex-1 overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                            <slot />
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
