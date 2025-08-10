<script setup>
import BackHeader from '@/components/BackHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage ,Link} from '@inertiajs/vue3';
import { rand } from '@vueuse/core';
import { onMounted, watch,ref } from 'vue';
import { useToast } from 'vue-toastification';
import { LoaderCircle, Pin } from 'lucide-vue-next';


const toast = useToast();
const page = usePage();

const total = ref(page.props.total);
const exam = page.props.exam.data;
const processing=ref(false);
const processed=ref(false);
defineProps({
    exam: {
        type: Object,
        required: true,
    },

    total: {
        type: Number,
        required: true,
    },
});
const form = useForm();
function setProgress(value) {
    const progressBar = document.getElementById('progressBar');
    value = Math.min(Math.max(value, 0), 100); // clamp between 0 and 100
    progressBar.style.width = value + '%';
    progressBar.setAttribute('aria-valuenow', value);
    progressBar.textContent = value + '%';
    if (value == 100) {
        processed.value=true;
    }
}
function changeStatus(){
    if(processing.value){
        // cancel
         processing.value=false;
         setProgress(0);
    }else{
        // start
        form.post(route('results.ai.start',exam.id),{
        onSuccess:()=>{
            processing.value=true;
        }
    });
    }
    
}
onMounted(() => {
    setProgress(0);
   window.Echo.private('exam.' + exam.id)
        .listen('ChangeProgressValue', (e) => {
            // console.log('Event received:', e);
            setProgress(e.value);
            total.value =e.total;
        });
});

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
    <Head title="Results" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="exam.results" :param="exam.id" :text="`Ai Correcting  : ${exam.title}`" />

            <div class="my-3 p-3">
                <div class="flex h-full w-full flex-col items-center justify-center gap-4">
                    <h2 class="text-3xl font-semibold">Correcting {{ total }} Answers</h2>
                    <div class="my-3 h-4 w-3/4 rounded-full bg-gray-200">
                        <div
                            id="progressBar"
                            class="rounded-full bg-blue-600 p-0.5 text-center text-xs leading-none font-medium text-white"
                            style="width: 0%"
                            role="progressbar"
                            aria-valuenow="0"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >
                            0%
                        </div>
                    </div>
                    <div class="my-3 flex h-full w-full items-center justify-center gap-5">
                       
                        <button
                        v-if="!processed && total!=0"
                        :disabled="processing"
                        @click="changeStatus"
                            type="button"
                            class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:outline-none"
                            
                        >
                           {{ processing ? 'Cancel' : 'Start'}}
                             <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        </button>
                        <Link
                        v-if="processed"
                        :href="route('exam.results',exam.id)"
                      class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-purple-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:outline-none"
                      >
                    See The Results 
                    </Link>
                        
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
