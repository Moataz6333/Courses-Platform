<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import Modal from '@/components/Modal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import OffersComponent from '@/components/OffersComponent.vue';

const page = usePage();
const toast = useToast();
defineProps({
    course: {
        type: Object,
        required: true,
    },
    offers: {
        type: Object,
        required: true,
    },
});
const course = page.props.course.data;
// const offers = page.props.offers.data;

// store
const form = useForm({
    course_id: course.id,
    value: '',
    end_at: '',
    code: '',
    isPublic: ref(true),
});
function togglePublic() {
    form.isPublic = !form.isPublic;
    form.code = '';
}
const storeOffer = function () {
    // console.log(form.isPublic);
    form.post(route('offers.store'), {
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
        }
    },
    { immediate: true },
);
</script>
<template>
    <Head title="Course-Offers" />
    <AppLayout>
        <div class="container">
            <!-- header -->
            <BackHeader back-route="courses.index" :text="'Offers of Course : ' + course.title" />
            <div v-if="course.price == 0" class="flex w-full justify-center">
                <h2 class="p-3 text-2xl font-medium">This Course is Already Free !</h2>
            </div>
            <!-- form -->
            <div v-else class="my-4 flex w-full items-center justify-between rounded-md p-4">
                <form @submit.prevent="storeOffer" class="w-full">
                    <div class="flex gap-3">
                        <div class="my-3 w-3/4">
                            <label for="title" class="text-xl">Offer Value</label>
                            <input
                                v-model="form.value"
                                class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                placeholder="offerple : 50 => -50% of course price"
                                type="text"
                                name="value"
                                id="value"
                                required
                            />
                            <InputError :message="form.errors.value" class="mt-2" />
                        </div>
                        <div class="my-3 w-1/4">
                            <label for="price" class="text-xl">Ends at (optional)</label>
                            <input
                                v-model="form.end_at"
                                class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                type="date"
                                name="end_at"
                                id="end_at"
                            />
                            <InputError :message="form.errors.end_at" class="mt-2" />
                        </div>
                    </div>
                    <div class="my-3 flex items-end gap-3">
                        <div class="w-1/4">
                            <label for="code" class="text-xl">Code</label>
                            <input
                                v-model="form.code"
                                :disabled="form.isPublic"
                                class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                :class="{ 'bg-gray-300 placeholder:text-gray-50': form.isPublic }"
                                placeholder="ex: #54c8h"
                                type="text"
                                name="code"
                                id="code"
                            />
                            <InputError :message="form.errors.code" class="mt-2" />
                        </div>
                        <div class="flex h-full items-end gap-2.5">
                            <input type="checkbox" v-model="form.isPublic" name="isPublic" id="isPublic" @click="togglePublic" />
                            <label for="period" class="text-sm">Public </label>
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
            <hr />
            <div class="my-3 p-4">
                 <OffersComponent :offers="page.props.offers" :course="course" />
            </div>
            
        </div>
    </AppLayout>
</template>
