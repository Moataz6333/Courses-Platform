<script setup>
import { Link ,useForm} from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
const props = defineProps({
    questions: {
        type: Object,
    },
});
const deleteForm=useForm({});

const deleteQuestion=(questionId)=>{
    if (confirm("Are you sure you want to delete this Question ?")) {
        deleteForm.delete(route('questions.destroy',questionId));
        
    }
}
</script>
<template>

    <div class="my-3">
        <h1 class="text-center text-2xl font-medium my-2">Questions</h1>

        <div v-for="(question,index) in questions.data" class="question my-2 p-2.5 rounded-md border-1 border-gray-400 ">
                    <div class="flex justify-between my-2">
                        <pre class="text-xl font-semibold ">{{ question.head }}</pre>
                        <p class="text-md text-green-600">{{ question.points }} pts</p>
                    </div>
                     <!-- Preview -->
                                    <div v-if="question.media" class="my-3">
                                        <img :src="question.media.path" alt="Preview" class="h-40 rounded-md border object-cover shadow" />
                                         
                                    </div>
                    <!-- options -->
                     <div v-if="question.has_options">
                        <ol class="list-decimal pl-5">
                            <li v-for="option in question.options" >
                                   <p class="text-lg font-semibold py-2 flex " :class="{'text-green-600' : option.isCorrect}">
                                     {{ option.answer }} <span v-if="option.isCorrect" class="text-green-600"><Check /></span>
                                   </p>
                            </li>
                        </ol>
                     </div>
                     <!-- written -->
                      <div v-else >
                            <p class="text-green-600 text-md">
                                {{ question.correct_ans }}
                            </p>
                      </div>
                       <div class="my-3 flex w-full justify-between">
                        <button
                        @click="deleteQuestion(question.id)"
                                 class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer"
                                 type="button">Delete</button>
                    <Link
                    :href="route('questions.edit',question.id)"
                        class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Edit
                    </Link>
                 
                </div>
        </div>
    </div>

</template>