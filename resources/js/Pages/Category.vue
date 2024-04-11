<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Create from './Category/Create.vue';

const props = defineProps({
    data: {type: Array},
})

const open = ref(false)

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
        </template>

        <div class="py-12">
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Categories</h3>
                </div>
                <div class="ml-4 mt-2 flex-shrink-0">
                    <button @click="open = true" type="button" class="relative inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create new category</button>
                </div>
                </div>
            </div>
            <Create v-model:open="open"></Create>
            <div v-if="props.data.length" class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0">
                <div v-for="(category, actionIdx) in props.data" :key="props.data.id" :class="[actionIdx === 0 ? 'rounded-tl-lg rounded-tr-lg sm:rounded-tr-none' : '', actionIdx === 1 ? 'sm:rounded-tr-lg' : '', actionIdx === props.data.length - 2 ? 'sm:rounded-bl-lg' : '', actionIdx === props.data.length - 1 ? 'rounded-bl-lg rounded-br-lg sm:rounded-bl-none' : '', 'group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500']">
                    <div class="mt-8">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">
                            {{ category.title }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
