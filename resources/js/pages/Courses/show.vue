<script setup>
import BackHeader from '@/components/BackHeader.vue';
import LessonsPreview from '@/components/LessonsPreview.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { BookOpenCheck,Pin } from 'lucide-vue-next';
import { watch } from 'vue';
import { useToast } from 'vue-toastification';
const page = usePage();
const toast = useToast();
defineProps({
    course: {
        type: Object,
        required: true,
    },
    lessons:{
        type:Object
    }
});
const course = page.props.course.data;

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
const Appearance = localStorage.getItem('appearance');
</script>
<template>
    <Head title="Add-Course" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="courses.index" text="Course Preview" />

            <div class="my-2 p-4">
                <!-- card -->
                <div class="w-full rounded-md border-1 border-t-gray-100 bg-gray-50 p-3 shadow-lg"
                :class="{'bg-gray-300 text-black' : Appearance=='dark'}"
                >
                    <h1 class="my-3.5 flex items-center gap-2 text-black text-2xl font-bold"><BookOpenCheck /> {{ course.title }}</h1>
                    <p class="text-md p-2 text-gray-600">
                        {{ course.description }}
                    </p>
                    <div class="my-3">
                        <p class="text-xl font-medium">
                            Price : <span class="text-green-500">{{ course.price==0 ? 'free' : course.price }}</span>
                        </p>
                    </div>
                </div>
                <!-- lessons -->
                <div class="my-2.5 w-full rounded-md border-1 border-t-gray-100 bg-gray-50 p-3 shadow-lg"
                :class="{'bg-gray-300 text-black' : Appearance=='dark'}"
                >
                    <h1 class="my-3.5 text-black flex items-center gap-2 text-2xl font-bold"><Pin /> Lessons</h1>        
                        <LessonsPreview :lessons="page.props.lessons.data" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
