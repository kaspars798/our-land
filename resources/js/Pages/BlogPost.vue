<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Edit from './Post/Edit.vue';

const props = defineProps({
    data: {type: Array},
    categories: {type: Array}
})

const open = ref(false)

const dataForEdit = ref(null)

const create = (editData) => {
    dataForEdit.value = editData
    open.value = true
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blog Posts</h2>
        </template>

        <div class="py-12">
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Blog Posts</h3>
                </div>
                <div class="ml-4 mt-2 flex-shrink-0">
                    <button @click="create(null)" type="button" class="relative inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create new post</button>
                </div>
                </div>
            </div>
            <Edit v-model:open="open" v-model:blog-post="dataForEdit" v-model:categories="props.categories"></Edit>
            <div v-if="props.data.length" class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0">
                <div v-for="(post, actionIdx) in props.data" :key="props.data.id" :class="[actionIdx === 0 ? 'rounded-tl-lg rounded-tr-lg sm:rounded-tr-none' : '', actionIdx === 1 ? 'sm:rounded-tr-lg' : '', actionIdx === props.data.length - 2 ? 'sm:rounded-bl-lg' : '', actionIdx === props.data.length - 1 ? 'rounded-bl-lg rounded-br-lg sm:rounded-bl-none' : '', 'group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500']">
                <div class="mt-8">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">
                    <a @click="create(post)" class="focus:outline-none">
                        <!-- Extend touch target to entire panel -->
                        <span class="absolute inset-0" aria-hidden="true" />
                        {{ post.title }}
                    </a>
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">{{ post.author.name }}: {{ post.created_at }}</p>
                    <p>{{ post.body }}</p>
                    <p class="mt-2 text-sm text-gray-500">Categories:
                        <span v-for="(category, idx) in post.categories" :key="post.categories.id" class="ml-2">
                            {{ category.val ? props.categories[idx].title : '' }}
                        </span>
                    </p>
                </div>
                <span class="pointer-events-none absolute right-6 top-6 text-gray-300 group-hover:text-gray-400" aria-hidden="true">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                    </svg>
                </span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
