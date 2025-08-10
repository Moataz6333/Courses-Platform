<script setup>
import BackHeader from '@/components/BackHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage ,Link } from '@inertiajs/vue3';
import { Pin, TestTubes } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
const toast = useToast();
const page = usePage();

const course = page.props.course.data;
const exams = page.props.exams.data;


defineProps({
    course: {
        type: Object,
        required: true,
    },
    exams:{
        type: Object,
    }
});


const deleteForm=useForm({});

const deleteExam=(examId)=>{
    if (confirm("Are you sure you want to delete this Exam ?")) {
        deleteForm.delete(route('exams.destroy',examId));
        
    }
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
    <Head title="Exams" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="courses.index" :text="`Exams of : ${course.title}`" />

            <div class="w-full flex justify-center p-3 my-3">
                 <Link
                                :href="route('exams.create',course.id)"
                                 class="bg-purple-500 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 cursor-pointer"
                            >
                              Create Exam <TestTubes class="pl-2" /> 
                            </Link>
            </div>

            <div class="my-3 p-4">
                 <table v-if="exams" class="w-full rounded-md border-1 border-gray-200 overflow-hidden">
                <thead class="text-white bg-gray-900 p-2 rounded-t-md">
                    <tr>
                        <th class="text-start p-3">#</th>
                        <th class="text-start ">title</th>
                        <th class="text-start">period</th>
                        <th class="text-start">questions</th>
                        <th class="text-start">results</th>
                        <th class="text-start">edit</th>
                        <th class="text-start">delete</th>
                    </tr>
                    </thead>
                <tbody>
                    <tr 
                    class="p-4 border-1 text-black border-gray-300 text-sm bg-gray-50 hover:bg-gray-100"
                    v-for="(exam,index) in exams">
                        <td scope="col" class="p-4 font-medium">{{ index+1 }}</td>
                        <td>{{ exam.title.slice(0,30) }}</td>
                        <td class="text-green-500">{{ exam.preiod ==0 ? 'Open' : exam.period }}</td>
                      
                       
                          <td>
                            <Link
                                :href="route('exams.show',exam.id)"
                                 class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 cursor-pointer"
                            >
                                Questions
                            </Link>
                        </td>
                          <td>
                            <Link
                                :href="route('exam.results',exam.id)"
                                 class="bg-purple-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-900 cursor-pointer"
                            >
                                Results
                            </Link>
                        </td>
                        <td>
                               <Link
                                :href="route('exams.edit',exam.id)"
                                 class="bg-cyan-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 cursor-pointer"
                            >
                                Edit
                            </Link>
                        </td>
                        <td>
                                <button
                                @click="deleteExam(exam.id)"
                                 class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer"
                                 type="button">Delete</button>
                        </td>
                    </tr>
                </tbody>

            </table>
               
            </div>
        </div>
    </AppLayout>
</template>
