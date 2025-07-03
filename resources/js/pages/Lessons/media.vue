<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Pin } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
const toast = useToast();
const page = usePage();
const lesson = page.props.lesson.data;
const form = useForm({
    media: '',
    mediaType: lesson.mediaType,
    public_id: lesson.public_id,
    resourceType:''
});

// Media preview URL
const previewUrl = ref(lesson.media);
const mediaUploading = ref(false);
let cloudinaryWidget = null;

function openWidget() {
    mediaUploading.value = true;
    cloudinaryWidget.open();
}
const delForm=useForm();
// Cancel upload
function cancelUpload() {
    previewUrl.value = '';
    delForm.delete(route('lessons.deleteMedia',lesson.id));
}
defineProps({
    lesson: {
        type: Object,
    },
});

// create lesson
const createMedia = function () {
    form.post(route('lessons.storeMedia', lesson.id), {
        onSuccess: () => {
            previewUrl.value = form.media;
            toast.success('Media uploaded successfully!');
        },
        onError: () => {
            toast.error('Failed to upload media.');
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
onMounted(() => {
    const folderName = `Course_${lesson.course_id}`;
    cloudinaryWidget = window.cloudinary.createUploadWidget(
        {
            cloudName: 'dtsobgekj',
            uploadPreset: 'upload-demo',
            sources: ['local'],
            multiple: false,
            resourceType: 'auto',
            maxFileSize: 1000000000, // ~1GB
             folder: folderName,
        },
        (error, result) => {
            if (!error && result && result.event === 'success') {
                 const uploaded = result.info;
                form.media = result.info.secure_url;
                form.resourceType = result.info.resource_type;
                form.public_id = result.info.public_id;

                if (uploaded.resource_type === 'image' && uploaded.format !== 'pdf') {
                    form.mediaType = 'image';
                } else if (uploaded.resource_type === 'video') {
                    form.mediaType = 'video';
                } else if ( uploaded.format === 'pdf') {
                    form.mediaType = 'pdf';
                } else {
                    toast.error('Unsupported file type');
                    mediaUploading.value = false;
                    return;
                }
                mediaUploading.value = false;
                createMedia();
            }
        },
    );
});
</script>

<template>
    <Head title="Add-Media" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="lessons.index" :param="lesson.course_id" :text="lesson.title" />

            <div class="my-3 p-4">
                <!-- lessons -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 bg-gray-50 p-3 shadow-sm">
                    <h1 class="my-3 flex items-center gap-2 text-xl font-bold"><Pin />Add Media</h1>
                </div>
                <!-- create -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 p-3 shadow-sm">
                    <div class="my-4 flex w-full items-center justify-between rounded-md p-4">
                        <div class="w-full">
                            <div v-if="form.processing" class="mb-4 w-full rounded bg-blue-100 px-4 py-2 text-blue-800 shadow">
                                <span class="inline-flex items-center">
                                    <LoaderCircle class="mr-2 h-5 w-5 animate-spin" />
                                    Uploading media , please wait...
                                </span>
                            </div>
                            <div v-if="delForm.processing" class="mb-4 w-full rounded bg-blue-100 px-4 py-2 text-blue-800 shadow">
                                <span class="inline-flex items-center">
                                    <LoaderCircle class="mr-2 h-5 w-5 animate-spin" />
                                    Deleting media , please wait...
                                </span>
                            </div>
                            <div class="my-3">
                                <label class="text-xl">Lesson Media</label>
                                <p class="mb-2 text-gray-500">* Upload an image, video, or PDF</p>

                                <button type="button" @click="openWidget" class="rounded bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">
                                    Upload Media
                                </button>

                                <div v-if="previewUrl" class="mt-4 rounded border bg-white p-3 shadow">
                                    <template v-if="form.mediaType == 'pdf'">
                                        <iframe :src="previewUrl" width="100%" height="400px" class="rounded"></iframe>
                                    </template>
                                    <template v-else-if="form.mediaType == 'video'">
                                        <video controls class="w-full rounded">
                                            <source :src="previewUrl" type="video/mp4" />
                                            Your browser does not support the video tag.
                                        </video>
                                    </template>
                                    <template v-else>
                                        <img :src="previewUrl" class="w-full max-w-md rounded" alt="Uploaded media" />
                                    </template>

                                    <button @click="cancelUpload" class="mt-3 rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700">
                                        Delete Media
                                    </button>
                                </div>

                                <InputError :message="form.errors.media" class="mt-2" />
                                <InputError :message="form.errors.mediaType" class="mt-2" />
                                <InputError :message="form.errors.public_id" class="mt-2" />
                                <InputError :message="form.errors.resourceType" class="mt-2" />
                            </div>

                            <div class="my-3 flex w-full justify-end">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
