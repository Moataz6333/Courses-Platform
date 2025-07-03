<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import LessonsCompnent from '@/components/LessonsCompnent.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage,router } from '@inertiajs/vue3';
import { LoaderCircle, Pin } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
const toast = useToast();
const page = usePage();

const form = useForm({
    title: '',
    description: '',
});

const course = page.props.course.data;


defineProps({
    course: {
        type: Object,
        required: true,
    },
    lessons: {
        type: Object,
    },
});

// create lesson
const createLesson = function () {
    form.post(route('lessons.store', course.id), {
        onSuccess: () => {
            form.reset();
        },
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
        } else if (flash.info) {
            toast.info(flash.info);
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Add-Course" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="courses.index" :text="course.title" />

            <div class="my-2 p-3">
                <!-- create -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 p-3 shadow-sm">
                    <h1 class="my-2 flex items-center gap-2 text-xl font-bold"><Pin />Add Lessons</h1>
                    <div class="my-2 flex w-full items-center justify-between rounded-md p-4">
                        <div class="grid w-full grid-cols-11 gap-2">
                            <div class=" col-span-4">
                                <label for="title" class="text-xl">Lesson Title</label>
                                <input
                                    v-model="form.title"
                                    class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    placeholder="Title"
                                    type="text"
                                    name="Title"
                                    id="title"
                                    required
                                />
                                <InputError :message="form.errors.title" class="mt-2" />
                            </div>
                            <div class=" col-span-6">
                                <label for="description" class="text-xl">Lesson Description</label>
                                <textarea
                                    v-model="form.description"
                                    class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    placeholder="Description (optional)"
                                    name="description"
                                    id="description"
                                ></textarea>
                                <InputError :message="form.errors.description" class="mt-2" />
                            </div>
                            <div class=" col-span-1 flex items-end">
                                <button
                                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                    type="button"
                                    @click="createLesson"
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- lessons -->
                <LessonsCompnent :lessons="page.props.lessons" />
            </div>
        </div>
    </AppLayout>
</template>
