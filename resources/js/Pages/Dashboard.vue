<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <ul>
                            <li><Link :href="route('welcome')" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">To Welcome Page</Link></li>
                            <li><Link :href="route('post.index')" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Blog Posts</Link></li>
                            <li><Link :href="route('category.index')" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Categories</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
