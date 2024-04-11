<script setup>
import { onBeforeMount, reactive, ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    open: {
        type: Boolean,
    },
    categories: {
      type: Array,
    },
})

const errors = ref({})
const success = ref('')

const blogPost = defineModel('blogPost')

watch(blogPost, (newPost, oldPost) => {
  form.id = newPost ? newPost.id : null
  form.title = newPost ? newPost.title : null
  form.body = newPost ? newPost.body : null
  form.categories = newPost ? newPost.categories : formCategories()
})

const emit = defineEmits(['update:open'])

const close = () => {
    emit('update:open', false)
}

const form = reactive({
    id: null,
    title: null,
    body: null,
    categories: [],
})

const formCategories = () => {
  let ctgrs = []
  for (let i=0; i<props.categories.length; i++) {
    ctgrs[i] = {id: i+1, val: false} 
  }

  return ctgrs
}

onBeforeMount(() => {
  form.categories = formCategories()
})

const submit = () => {
(form.id ? axios.patch(route('post.update', {id:form.id}), form) : axios.post(route('post.store'), form))
  .then(response => {
      success.value = response.data.message
      location.reload()
    })
    .catch(error => {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
        if (error.response.status === 405) {
          location.reload()
        }
    })
}

</script>

<template>
  <TransitionRoot as="template" :show="props.open">
    <Dialog as="div" class="relative z-10" @close="close">
      <div class="fixed inset-0" />

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
            <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
              <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                <form @submit.prevent="submit()" class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                  <div class="flex-1">
                    <!-- Header -->
                    <div class="bg-gray-50 px-4 py-6 sm:px-6">
                      <div class="flex items-start justify-between space-x-3">
                        <div class="space-y-1">
                          <DialogTitle class="text-base font-semibold leading-6 text-gray-900">New blog post</DialogTitle>
                          <p class="text-sm text-gray-500">Get started by filling in the information below to create your new blog post.</p>
                        </div>
                        <div class="flex h-7 items-center">
                          <button type="button" class="relative text-gray-400 hover:text-gray-500" @click="close">
                            <span class="absolute -inset-2.5" />
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Divider container -->
                    <div class="space-y-6 py-6 sm:space-y-0 sm:divide-y sm:divide-gray-200 sm:py-0">
                      <!-- Post title -->
                      <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                        <div>
                          <label for="title" class="block text-sm font-medium leading-6 text-gray-900 sm:mt-1.5">Post title</label>
                        </div>
                        <div class="sm:col-span-2">
                          <input v-model="form.title" type="text" name="title" id="title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                          <div v-if="errors?.title" class="text-red-900">{{ errors.title[0] }}</div>
                        </div>
                      </div>

                      <!-- Post body -->
                      <div class="space-y-2 px-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0 sm:px-6 sm:py-5">
                        <div>
                          <label for="body" class="block text-sm font-medium leading-6 text-gray-900 sm:mt-1.5">Description</label>
                        </div>
                        <div class="sm:col-span-2">
                          <textarea v-model="form.body" id="body" name="body" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                          <div v-if="errors?.body" class="text-red-900">{{ errors.body[0] }}</div>
                        </div>
                      </div>

                      <!-- Post categories -->
                      <fieldset>
                        <h3 class="mb-5">Categories</h3>
                        <legend class="sr-only">Categories</legend>
                        <div class="space-y-5">
                          <div class="sm:col-span-2" v-for="(category, idx) in props.categories" :key="props.categories.id">
                            <div class="relative flex items-start">
                              <div class="flex h-6 items-center">
                                <input v-model="form.categories[idx].val" :id="category.title" :name="category.title" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                              </div>
                              <div class="ml-3 text-sm leading-6">
                                <label :for="category.title" class="font-medium text-gray-900">{{ category.title }}</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>

                    </div>
                  </div>

                  <!-- Action buttons -->
                  <div class="flex-shrink-0 border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="flex justify-end space-x-3">
                      <button v-if="form.id" :disabled="form.processing" type="button" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-red-50 hover:text-gray-900" @click="form.delete(route('post.destroy', {id:form.id})); close()">Delete</button>
                      <button :disabled="form.processing" type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" @click="close">Cancel</button>
                      <button :disabled="form.processing" type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{form.id ? 'Update' : 'Create'}}</button>
                      <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                      >
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                      </Transition>
                    </div>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>