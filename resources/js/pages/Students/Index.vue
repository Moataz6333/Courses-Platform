<script setup>
import MagnifyingGlass from '@/components/Icons/MagnifyingGlass.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
// import axios from 'axios';

defineProps({
    students: {
        type: Object,
        required: true,
    },
});
const page = usePage();
//let students = page.props.students;
// search
let search = ref(page.props.search || ''),
    pageNumber = ref('1');
// Debounce function
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};
// Watch search with debounce
watch(
    search,
    debounce((value) => {
        pageNumber.value = '1'; // Reset to first page on new search
        let url = new URL(route('students.index'));
        url.searchParams.append('page', pageNumber.value);
        if (value) {
            url.searchParams.append('search', value);
        }

        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300),
); // 300ms debounce delay

const updatedPageNumber = (link) => {
    pageNumber.value = link.url.split('=')[1];
    let url = new URL(route('students.index'));
    url.searchParams.append('page', pageNumber.value);
    if (search.value) {
        url.searchParams.append('search', search.value);
    }
    router.visit(url, {
        preserveScroll: true,
    });
};

// delete
const deleteForm = useForm({});

const deleteStudent = (studentId) => {
    if (confirm('Are you sure you want to delete this student ?')) {
        deleteForm.delete(route('students.destroy', studentId));
    }
};
</script>
<template>
    <AppLayout>
        <Head title="Students" />
        <div class="py-4">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">Students</h1>
                            <p class="mt-2 text-sm text-gray-700">A list of all the Students.</p>
                        </div>
                    </div>

                    <div  class="mt-6 flex flex-col justify-between sm:flex-row">
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
                    <Pagination :data="page.props.students" :updatedPageNumber="updatedPageNumber" />
                    <div class="mt-8 flex flex-col">
                        <table class="w-full overflow-hidden rounded-md border-1 border-gray-200">
                            <thead class="rounded-t-md bg-gray-900 p-2 text-white">
                                <tr>
                                    <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-white sm:pl-6">Name</th>
                                    <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-white sm:pl-6">Email</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Show</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Delete</th>
                                    <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-6" />
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="student in students.data" class="border-1 w-full border-gray-300 bg-gray-50 p-4 text-sm hover:bg-gray-100" >
                                    <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6">
                                        {{ student.name }}
                                    </td>
                                    <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">{{ student.email }}</td>
                                    <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">
                                        <button class="text-indigo-600 hover:text-indigo-900">Show</button>
                                    </td>

                                    <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">
                                        <button @click="deleteStudent(student.id)" class="ml-2 text-indigo-600 hover:text-indigo-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
