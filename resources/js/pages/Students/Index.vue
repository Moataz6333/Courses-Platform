<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import MagnifyingGlass from '@/components/Icons/MagnifyingGlass.vue';
import Pagination from '@/components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
// import axios from 'axios';

defineProps({
    students:{
        type:Object,
        required:true
    }
});
// search
let search=ref(usePage().props.search),pageNumber=ref("1");
let studentsUrl= computed(()=>{
    let url =new URL(route("students.index"));
    url.searchParams.append("page",pageNumber.value);
    if(search.value){
        url.searchParams.append("search",search.value);
    }
    return url;
});
const updatedPageNumber=(link)=>{
       pageNumber.value=link.url.split("=")[1];
    
}
watch(()=>studentsUrl.value,(newUrl)=>{
    router.visit(newUrl,{
        preserveScroll:true,
        preserveState:true,
        replace:true,
    });
});
watch(()=>search.value,(value)=>{
    if(value){
        // reset to page one after multiple search
        pageNumber.value=1;
    }
});

// delete
const deleteForm=useForm({});

const deleteStudent=(studentId)=>{
    if (confirm("Are you sure you want to delete this student ?")) {
        deleteForm.delete(route('students.destroy',studentId));
        
    }
}

</script>
<template>
     <AppLayout>
        <Head title="Students" />
    <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">Students</h1>
                        <p class="mt-2 text-sm text-gray-700">A list of all the Students.</p>
                    </div>

                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                        <Link
                          
                            :href="route('students.create')"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                        >
                            Add Student
                     </Link>
                    </div>
                </div>
                       
                <div class="mt-6 flex flex-col justify-between sm:flex-row">
                    <div class="relative col-span-3 text-sm text-gray-800">
                        <div class="pointer-events-none absolute top-0 bottom-0 left-0 flex items-center pl-2 text-gray-500">
                            <MagnifyingGlass />
                        </div>

                        <input
                            v-model="search"
                            type="text"
                            autocomplete="off"
                            placeholder="Search students data..."
                            id="search"
                            class="block rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>

                <div class="mt-8 flex flex-col">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="ring-opacity-5 relative overflow-hidden shadow ring-1 ring-black md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                           <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                                            <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                            <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">Email</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Class</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Section</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created At</th>
                                            <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-6" />
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">

                                        <tr v-for="student in students.data" :key="student.id">
                                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6">{{ student.id }}</td>
                                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6">{{ student.name }}</td>
                                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">{{ student.email }}</td>
                                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">{{ student.class.name }}</td>
                                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">{{ student.section.name }}</td>
                                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">{{ student.created_at }}</td>

                                            <td class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6">
                                                <Link :href=" route('students.edit',student.id)" class="text-indigo-600 hover:text-indigo-900"> Edit </Link>
                                                <button @click="deleteStudent(student.id)" class="ml-2 text-indigo-600 hover:text-indigo-900">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <Pagination :data="students" :updatedPageNumber="updatedPageNumber" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     </AppLayout>
</template>
