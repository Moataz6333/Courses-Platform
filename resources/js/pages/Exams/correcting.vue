<script setup>
import BackHeader from '@/components/BackHeader.vue';
import Modal from '@/components/Modal.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage ,useForm,Link} from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import InputError from '@/components/InputError.vue';
import { onMounted } from 'vue';
import { Mails,Gem } from 'lucide-vue-next';


const toast = useToast();
const page = usePage();
let pageNumber = ref('1');
let showProgressBar = ref(false);
let processed = ref(false);
let processing = ref(false);
const total = page.props.total;
const allCorrected = page.props.allCorrected;
const exam = page.props.exam.data;
const results = page.props.results.data;

defineProps({
    exam: {
        type: Object,
        required: true,
    },
    results: {
        type: Object,
    },
    grades: {
        type: Array,
    },

    total: {
        type: Number,
        required: true,
    },
    allCorrected: {
        type: Boolean,
        required: true,
    },
});
const form=useForm({
    result_id:'',
    points:[],
});
const Emailform = useForm();
const updatedPageNumber = (link) => {
    pageNumber.value = link.url.split('=')[1];
    let url = new URL(route('exam.results', exam.id));
    url.searchParams.append('page', pageNumber.value);
    router.visit(url, {
        preserveScroll: true,
    });
};
const gradeClass = function (grade) {
    switch (grade) {
        case 'A':
            return 'text-green-700 font-bold';
        case 'A-':
            return 'text-green-600 font-semibold';
        case 'B+':
            return 'text-blue-600';
        case 'B':
            return 'text-blue-500';
        case 'B-':
            return 'text-blue-400';
        case 'C+':
            return 'text-yellow-600';
        case 'C':
            return 'text-yellow-500';
        case 'C-':
            return 'text-yellow-400';
        case 'F':
            return 'text-red-600 font-bold';
        default:
            return 'text-gray-600';
    }
};

const showModal = ref(false);
const rowId = ref(0);
let answers = reactive({});
const openAnswersModal = function (ans,id,row_Id) {
    form.reset();
    form.result_id=id;
    answers = ans;
    showModal.value = true;
    rowId.value=row_Id;
};
const correctForm=()=>{
      
    form.post(route('result.correct'),{
        onSuccess:()=>{
            showModal.value=false;
            form.reset();
             let row=document.getElementById(rowId.value);
            row.style.display='none';
        }
    });

}
function setProgress(value) {
    const progressBar = document.getElementById('progressBar');
    value = Math.min(Math.max(value, 0), 100); // clamp between 0 and 100
    progressBar.style.width = value + '%';
    progressBar.setAttribute('aria-valuenow', value);
    progressBar.textContent = value + '%';
    if (value == 100) {
        processed.value=true;
        router.visit('exam.results',exam.id);
        toast.success('Results Sended successsfully');
    }
}
function changeStatus(){
        form.get(route('results.send',exam.id),{
        onSuccess:()=>{
            processing.value=true;
            showProgressBar.value=true;
        }
    });
    
    
}
    

onMounted(() => {
   
   window.Echo.private('exam.' + exam.id)
        .listen('ChangeEmailProgressValue', (e) => {
            console.log('Event received:', e);
            setProgress(e.value);
            
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
            <BackHeader back-route="exams.index" :param="exam.course.id" :text="`Correcting  : ${exam.title}`" />

            <div class="my-3 p-3">
                <div class="w-full flex justify-center p-3 flex-col items-center">
                    
                    <button
                        v-if="!processed && allCorrected"
                        :disabled="processing"
                        @click="changeStatus"
                            type="button"
                            class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:outline-none"
                            
                        >
                            Send To Students  <Mails class="ml-2"/>
                        </button>
                     <div 
                      id="progressBarDiv"
                     class="my-3 h-4 w-3/4 rounded-full bg-gray-200">
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
                    <Link
                    v-if="!allCorrected"
                    :href="route('results.ai.correct',exam.id)"
                     class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-purple-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:outline-none"
                    >
                    AI Correcting <Gem/>
                    </Link>
                </div>
                <Pagination :data="page.props.results" :updatedPageNumber="updatedPageNumber" />
                <table v-if="results" class="w-full overflow-hidden rounded-md border-1 border-gray-200">
                    <thead class="rounded-t-md bg-gray-900 p-2 text-white">
                        <tr>
                            <th class="p-3 text-start">#</th>
                            <th class="text-start">name</th>
                            <th class="text-start">email</th>
                            <th class="text-start">correct (total:{{ total }})</th>
                            <th class="text-start">grade</th>
                            <th class="text-start">Submittd At</th>
                            <th class="text-start">status</th>
                            <th class="text-start">Written Answers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(result, index) in results" :key="index" :id="`row${index}`" class="border-1 border-gray-300 bg-gray-50 p-4 text-sm text-black hover:bg-gray-100">
                            <td scope="col" class="p-4 font-medium">{{ index + 1 }}</td>
                            <td>{{ result.name }}</td>
                            <td>{{ result.email }}</td>
                            <td>{{ result.correct }}</td>
                            <td :class="gradeClass(result.grade)">{{ result.grade }}</td>
                            <td>{{ result.created_at }}</td>
                            <td class="text-green-500" :class="{ 'text-red-500': result.status == 'pending' }">{{ result.status }}</td>
                            <td>
                                <button
                                    @click="openAnswersModal(result.answers,result.id,`row${index}`)"
                                    type="button"
                                    class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-purple-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-600 focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:outline-none"
                                >
                                    correct
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- modal -->
                <Modal v-if="answers" :show="showModal" title="Answers" @close="showModal = false">
                    <form @submit.prevent="correctForm" class="w-full">
                    <div class="flex w-full flex-col justify-center gap-5">
                        <div v-for="(answer,index) in answers" class="my-3" :key="index" >
                            <div class="flex gap-3">
                                <h3 class="text-2xl font-bold">{{ answer.question }}</h3>
                                <p class="text-green-500">{{ answer.points }}</p>
                            </div>
                            <p class="text-xl font-medium text-gray-500">
                                {{ answer.answer }}
                            </p>
                            <p v-if="answer.corrected_by == 'ai'" class="text-md my-2.5 text-center font-medium text-green-700">Ai grade: {{ answer.grade }} % with : {{ answer.ai_grade }}</p> 
                            <p v-if="answer.corrected_by == 'teacher'" class="text-md my-2.5 text-center font-medium text-green-700">Teacher grade: {{ answer.ai_grade /100}} </p> 
                            <input
                            v-model="form.points[index]"
                                class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                placeholder="points"
                                type="text"
                                name="correct"
                                
                                required
                            />
                             <InputError :message="form.errors[`points.${index}`]" class="mt-2" />
                        </div>

                        <div class="my-3 flex w-full justify-end">
                            <button
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                type="submit"
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                    </form>
                </Modal>
            </div>
        </div>
    </AppLayout>
</template>
