<script setup>
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useToast } from 'vue-toastification';
defineProps({
    socialmedia: {
        type: Object,
        required: true,
    },
});
const toast = useToast();
const page = usePage();
const facebook = useForm({
    name: 'facebook',
    link: page.props.socialmedia.facebook ?? '',
});
const faceBookForm = function () {
    facebook.post(route('socialmedia.store'));
};
const instagram = useForm({
    name: 'instagram',
    link: page.props.socialmedia.instagram ?? '',
});
const instagramForm = function () {
    instagram.post(route('socialmedia.store'));
};
const whatsapp = useForm({
    name: 'whatsapp',
    link: page.props.socialmedia.whatsapp ?? '',
});
const whatsappForm = function () {
    whatsapp.post(route('socialmedia.store'));
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
    <Head title="Social Media" />
    <AppLayout>
        <div class="container">
            <!-- header -->
            <div class="header flex w-full items-center gap-3 rounded-b-2xl border-b-2 p-3 px-6">
                <h1 class="text-bold text-2xl font-medium ">Social Media</h1>
            </div>
            <!-- form -->
            <div class="my-4 flex flex-col w-full items-center justify-between rounded-md p-4">
                <!-- facebook -->
                <div class="my-3 w-full">
                    <form @submit.prevent="faceBookForm" class="w-full">
                        <div class="flex w-full gap-3.5">
                            <label for="title" class="text-xl text-blue-500">FaceBook </label>
                            <input
                                v-model="facebook.link"
                                class="block w-3/4 rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm"
                                placeholder="Link"
                                type="text"
                                name="facebook"
                                id="facebook"
                                required
                            />
                            <button
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                type="submit"
                            >
                                <span v-if="page.props.socialmedia.facebook != ''">Update</span>
                                <span v-else>Add</span>
                            </button>
                        </div>
                        <InputError :message="facebook.errors.link" class="mt-2" />
                    </form>
                </div>
                <!-- instagram -->
                <div class="my-3 w-full">
                    <form @submit.prevent="instagramForm" class="w-full">
                        <div class="flex w-full gap-3.5">
                            <label for="title" class="text-xl text-fuchsia-500">Instagram </label>
                            <input
                                v-model="instagram.link"
                                class="block w-3/4 rounded-lg border-0 py-2 pl-5 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-fuchsia-600 focus:ring-inset sm:text-sm"
                                placeholder="Link"
                                type="text"
                                name="instagram"
                                id="instagram"
                                required
                            />
                            <button
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-fuchsia-700 focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                type="submit"
                            >
                                <span v-if="page.props.socialmedia.instagram != ''">Update</span>
                                <span v-else>Add</span>
                            </button>
                        </div>
                        <InputError :message="instagram.errors.link" class="mt-2" />
                    </form>
                </div>
                <!-- whatsapp -->
                <div class="my-3 w-full">
                    <form @submit.prevent="whatsappForm" class="w-full">
                        <div class="flex w-full gap-3.5">
                            <label for="title" class="text-xl text-green-500">Whatsapp </label>
                            <input
                                v-model="whatsapp.link"
                                class="block w-3/4 rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-green-600 focus:ring-inset sm:text-sm"
                                placeholder="Link"
                                type="text"
                                name="whatsapp"
                                id="whatsapp"
                                required
                            />
                            <button
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                type="submit"
                            >
                                <span v-if="page.props.socialmedia.whatsapp != ''">Update</span>
                                <span v-else>Add</span>
                            </button>
                        </div>
                        <InputError :message="whatsapp.errors.link" class="mt-2" />
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
