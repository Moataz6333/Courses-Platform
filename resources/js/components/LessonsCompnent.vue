<script setup>
import { Link ,useForm} from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
const props = defineProps({
    lessons: {
        type: Object,
    },
});
const deleteForm=useForm({});

const deleteLesson=(lessonId)=>{
    if (confirm("Are you sure you want to delete this Lesson ?")) {
        deleteForm.delete(route('lessons.destroy',lessonId));
        
    }
}
</script>

<template>
    <div class="my-3 p-4">
               <table v-if="props.lessons" class="w-full rounded-md border-1 border-gray-200 overflow-hidden">
                   <thead class="text-white bg-gray-900 p-2 rounded-t-md">
                       <th class="text-start p-3">#</th>
                       <th class="text-start ">title</th>
                       <th class="text-start">description</th>
                       <th class="text-start">media</th>
                       <th class="text-start">edit</th>
                       <th class="text-start">delete</th>
                   </thead>
                   <tbody>
                       <tr 
                       class="p-4 border-1 border-gray-300 text-sm bg-gray-50 hover:bg-gray-100"
                       v-for="(lesson,index) in props.lessons.data">
                           <td scope="col" class="p-4 font-medium">{{ index+1 }}</td>
                           <td>{{ lesson.title.slice(0,30) }}</td>
                           <td>{{ lesson.description.slice(0,30) + "..." }}</td>
                           <td>
                               <Link
                                   :href="route('lessons.media',lesson.id)"
                                    class="bg-purple-500 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 cursor-pointer"
                               >
                                   Media
                               </Link>
                           </td>
                           <td>
                                  <Link
                                   :href="route('lessons.edit',lesson.id)"
                                    class="bg-cyan-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 cursor-pointer"
                               >
                                   Edit
                               </Link>
                           </td>
                           <td>
                                   <button
                                   @click="deleteLesson(lesson.id)"
                                    class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer"
                                    type="button">Delete</button>
                           </td>
                       </tr>
                   </tbody>
   
               </table>
           </div>
</template>
