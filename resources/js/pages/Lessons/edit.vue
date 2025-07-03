<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Pin } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
const toast = useToast();
const page = usePage();
defineProps({
    lesson: {
        type: Object,
        required: true,
    },
});

const lesson = page.props.lesson.data;
const form = useForm({
    title: lesson.title,
    description: lesson.description,
    thumbnail: null,
});

const previewUrl = ref(lesson.thumbnail ?? null);
const handleThumbnailChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.thumbnail = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

// create lesson
const updateLesson = function () {
    form.post(route('lessons.update', lesson.id),{
        forceFormData: true,
    });
};

// flash messages
watch(
    () => page.props.flash,
    (flash) => {
        if (flash.success) {
            toast.success(flash.success);
        } else if (flash.error) {
            toast.error(flash.error);
        }
    },
    { immediate: true },
);
</script>
<template>
    <Head title="Edit-Lesson" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="lessons.index" :param="lesson.course_id" :text="lesson.title" />

            <div class="my-3 p-4">
                <!-- lessons -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 bg-gray-50 p-3 shadow-sm">
                    <h1 class="my-3 flex items-center gap-2 text-xl font-bold"><Pin />Edit Lesson</h1>
                </div>
                <!-- create -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 p-3 shadow-sm">
                    <div class="my-4 flex w-full items-center justify-between rounded-md p-4">
                        <form @submit.prevent="updateLesson" class="w-full">
                            <div class="my-3">
                                <label for="title" class="text-xl">Lesson Title</label>
                                <input
                                    v-model="form.title"
                                    class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    placeholder="Title"
                                    type="text"
                                    name="Title"
                                    id="title"
                                    required
                                />
                                <InputError :message="form.errors.title" class="mt-2" />
                            </div>
                            <div class="my-3">
                                <label for="description" class="text-xl">Lesson Description</label>
                                <textarea
                                    v-model="form.description"
                                    class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    placeholder="Description (optional)"
                                    name="description"
                                    id="description"
                                    rows="5"
                                ></textarea>
                                <InputError :message="form.errors.description" class="mt-2" />
                            </div>
                            <!-- thumbnail -->
                            <div class="my-3">
                                <label for="thumbnail" class="text-xl">Thumbnail Image</label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleThumbnailChange"
                                    class="mt-2 block w-3/4 rounded-lg border border-gray-300 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700"
                                    id="thumbnail"
                                />
                                <InputError :message="form.errors.thumbnail" class="mt-2" />

                                <!-- Preview -->
                                <div v-if="previewUrl" class="mt-3">
                                    <p class="mb-2 text-sm text-gray-600">Preview:</p>
                                    <img :src="previewUrl" alt="Preview" class="h-40 rounded-md border object-cover shadow" />
                                </div>
                            </div>

                            <div class="my-3 flex w-full justify-end">
                                <button
                                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                    type="submit"
                                >
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
