<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { watch,ref } from 'vue';
import { useToast } from 'vue-toastification';
const page = usePage();
const toast = useToast();
defineProps({
    course: {
        type: Object,
        required: true,
    },
     tracks:{
        type:Object,
        required:true
    }
});
const course = page.props.course.data;
const form = useForm({
    id: course.id,
    title: course.title,
    description: course.description,
    keyWords: course.keyWords,
    price: course.price,
    thumbnail: null,
     track_id: course.track_id,
});
const previewUrl = ref(course.thumbnail ?? null);
const handleThumbnailChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.thumbnail = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const updateCourse = function () {
    form.post(route('courses.update', course), {
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
    <Head title="Edit-Course" />
    <AppLayout>
        <div class="container">
            <!-- header -->
            <BackHeader back-route="courses.index" text="Edit Course" />
            <!-- logic -->
            <!-- form -->
            <div class="my-4 flex w-full items-center justify-between rounded-md p-4">
                <form @submit.prevent="updateCourse" class="w-full">
                    <div class="my-3">
                        <label for="title" class="text-xl">Course Title</label>
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
                        <label for="description" class="text-xl">Course Description</label>
                        <textarea
                            v-model="form.description"
                            class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                            placeholder="Description"
                            name="description"
                            id="description"
                            rows="5"
                            required
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>
                    <!-- track -->
                     <div class="my-3">
                                        <label
                                            for="track"
                                            class="block text-sm font-medium text-gray-700"
                                            >Track</label
                                        >
                                        <select
                                         v-model="form.track_id"
                                         placeholder="Course Track"
                                            id="track_id"
                                            class="mt-1 block w-3/4 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{'text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.class_id}"
                                        >
                                           
                                            <option v-for="track in tracks.data" :key="track.id" :value=" track.id" :selected="course.track_id==track.id">{{track.name}}</option>
                                        </select>
                                        <InputError :message="form.errors.track_id" class="mt-2" />
                                    </div>
                    <div class="my-3">
                        <label for="price" class="text-xl">Course Price</label>
                        <input
                            v-model="form.price"
                            class="mt-2.5 block w-1/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                            placeholder="Price in (EGP)"
                            type="text"
                            name="price"
                            id="price"
                            required
                        />
                        <InputError :message="form.errors.price" class="mt-2" />
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
                     <!-- key words -->
                       <div class="my-3">
                        <label for="keyWords" class="text-xl">Key Words</label>
                        <textarea
                            v-model="form.keyWords"
                            class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                            placeholder="ex: Programming java oop ..."
                            name="keyWords"
                            id="keyWords"
                            rows="5"
                            required
                        ></textarea>
                        <InputError :message="form.errors.keyWords" class="mt-2" />
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
    </AppLayout>
</template>
