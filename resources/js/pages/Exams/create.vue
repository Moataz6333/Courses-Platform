<script setup>
import BackHeader from '@/components/BackHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Pin } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import InputError from '@/components/InputError.vue';
const toast = useToast();
const page = usePage();

const course = page.props.course.data;

defineProps({
    course: {
        type: Object,
        required: true,
    }
});

const isOpened=ref(false);
const isNow=ref(false);
const togglePeriod=()=>{
    isOpened.value=!isOpened.value;
    if(isOpened.value){
        form.period="";
    }
  
}
const DateToggle=()=>{
    isNow.value=!isNow.value;
    if(isNow.value){
        form.reset('startDate');
    }
}
const form=useForm({
    title:'',
    period:'',
    startDate:'',
});
const createExam=()=>{
    form.post(route('exams.store',course.id));
}


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
    <Head title="Add-Exam" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="exams.index" :param="course.id" :text="`Create Exam for : ${course.title}`" />

            <div class="my-3 p-4">
                <!-- create -->
                <div class="my-2 w-full rounded-md border-1 border-t-gray-100 p-3 shadow-sm">
                    <div class="my-4 flex w-full items-center justify-between rounded-md p-4">
                        <form @submit.prevent="createExam" class="w-full">
                            <div class="my-3">
                                <label for="title" class="text-xl">Exam Title</label>
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
                                <label for="period" class="text-xl">Exam Period </label>
                                <div class="w-full flex gap-4 items-center">
                                    <input
                                    v-model="form.period"
                                    :disabled="isOpened"
                                    class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    :class="{'bg-gray-300 placeholder:text-gray-50':isOpened}"
                                    type="text"
                                    placeholder="In Minutes"
                                    name="period"
                                    id="period"
                                    
                                />
                                <div class="flex gap-2.5 items-center">
                                    <input type="checkbox" v-model="isOpened" name="opend" id="opend" @click="togglePeriod">
                                    <label for="period" class="text-sm">Opened </label>
                                </div>
                                </div>
                                <InputError :message="form.errors.period" class="mt-2" />
                            </div>
                            <div class="my-3">
                                <label for="title" class="text-xl">Start Date </label>
                                <div class="w-full flex gap-4 items-center">
                                    <input
                                    v-model="form.startDate"
                                     :disabled="isNow"
                                    class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                    :class="{'bg-gray-300 placeholder:text-gray-50':isNow}"
                                    type="datetime-local"
                                    name="startDate"
                                    id="startDate"
                                    
                                />
                                <div class="flex gap-2.5 items-center">
                                    <input type="checkbox" name="now" v-model="isNow" id="now" @click="DateToggle">
                                    <label for="now" class="text-sm">Now </label>
                                </div>
                                </div>
                                <InputError :message="form.errors.startDate" class="mt-2" />
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
