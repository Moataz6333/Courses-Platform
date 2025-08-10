<script setup>
import { ref } from 'vue';
import { Link,useForm } from '@inertiajs/vue3';
const props=defineProps({
    courses:{
        type:Object,
    }
});
const deleteForm=useForm({});

const deleteCourse=(courseId)=>{
    if (confirm("Are you sure you want to delete this Course ?")) {
        deleteForm.delete(route('courses.destroy',courseId));
        
    }
}

</script>
<template>
        <div class="my-3 p-4">
            <table v-if="props.courses" class="w-full rounded-md border-1 border-gray-200 overflow-hidden">
                <thead class="text-white bg-gray-900 p-2 rounded-t-md">
                    <tr>
                        <th class="text-start p-3">#</th>
                    <th class="text-start ">title</th>
                    <th class="text-start">price</th>
                    <th class="text-start">show</th>
                    <th class="text-start">lessons</th>
                    <th class="text-start">exams</th>
                    <th class="text-start">offers</th>
                    <th class="text-start">enrollments</th>
                    <th class="text-start">edit</th>
                    <th class="text-start">delete</th>
                    </tr>
                </thead>
                <tbody >
                    <tr 
                    class="p-4 border-1 border-gray-300 text-sm bg-gray-50 text-black hover:bg-gray-100"
                    v-for="(course,index) in props.courses.data">
                        <td scope="col" class="p-4 font-medium">{{ index+1 }}</td>
                        <td>{{ course.title.slice(0,30) }}</td>
                      
                        <td class="text-green-500">{{ course.price ==0 ? 'Free' : course.price }}</td>
                      
                        <td>
                            <Link
                                :href="route('courses.show',course.id)"
                                 class="bg-blue-500 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 cursor-pointer"
                            >
                                Show
                            </Link>
                        </td>
                        <td>
                            <Link
                                :href="route('lessons.index',course.id)"
                                 class="bg-purple-500 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 cursor-pointer"
                            >
                                Lessons
                            </Link>
                        </td>
                          <td>
                            <Link
                                :href="route('exams.index',course.id)"
                                 class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 cursor-pointer"
                            >
                                Exams
                            </Link>
                        </td>
                          <td>
                            <Link
                                :href="route('course.offers',course.id)"
                                 class="bg-green-700 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-800 cursor-pointer"
                            >
                                Offers
                            </Link>
                        </td>
                          <td>
                            <Link
                                :href="route('course.enrollments',course.id)"
                                 class="bg-teal-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-900 cursor-pointer"
                            >
                                Enrollments
                            </Link>
                        </td>
                        <td>
                               <Link
                                :href="route('courses.edit',course.id)"
                                 class="bg-cyan-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 cursor-pointer"
                            >
                                Edit
                            </Link>
                        </td>
                        <td>
                                <button
                                @click="deleteCourse(course.id)"
                                 class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer"
                                 type="button">Delete</button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
</template>