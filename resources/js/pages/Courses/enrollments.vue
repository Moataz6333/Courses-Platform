<script setup>
import BackHeader from '@/components/BackHeader.vue';
import Modal from '@/components/Modal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref,reactive } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { watch,computed } from 'vue';
import { router } from '@inertiajs/vue3';
import MagnifyingGlass from '@/components/Icons/MagnifyingGlass.vue';

const page = usePage();
// const enrollments = page.props.enrollments.data;
let pageNumber=ref("1");
defineProps({
    enrollments: {
        type: Object,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    courseType: {
        type: String,
        required: true,
    },
    courseId: {
        type: String,
        required: true,
    },
});
// search
let search = ref(usePage().props.search || '');

// Debounce function
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

// Watch search with debounce
watch(search, debounce((value) => {
    pageNumber.value = '1'; // Reset to first page on new search
    let url = new URL(route("course.enrollments", page.props.courseId));
    url.searchParams.append("page", pageNumber.value);
    if (value) {
        url.searchParams.append("search", value);
    }
    
    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}, 300)); // 300ms debounce delay

const updatedPageNumber=(link)=>{
       pageNumber.value=link.url.split("=")[1];
          let url =new URL(route("course.enrollments",page.props.courseId));
         url.searchParams.append("page",pageNumber.value);
         if(search.value){
             url.searchParams.append("search", search.value);
         }
        router.visit(url,{
            preserveScroll:true,
        });
}
const showModal = ref(false);
let transaction = reactive({});
const openTransactionModal=function(tr){
    transaction=tr;
    showModal.value=true;
}
</script>
<template>
    <Head title="Enrollments" />
    <AppLayout>
        <BackHeader back-route="courses.index" :text="`Enrollments of Course : ${page.props.title}`" />
        <div class="my-3 p-4">
            <!-- search -->
                     
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
              <Pagination :data="page.props.enrollments" :updatedPageNumber="updatedPageNumber" />
            <table v-if="enrollments" class="w-full overflow-hidden rounded-md border-1 border-gray-200">
                <thead class="rounded-t-md bg-gray-900 p-2 text-white">
                    <tr>
                        <th class="p-3 text-start">#</th>
                    <th class="text-start">name</th>
                    <th class="text-start">email</th>
                    <th class="text-start">date</th>
                    <th v-if="page.props.courseType== 'paid'" class="text-start">paid</th>
                    <th v-if="page.props.courseType== 'paid'" class="text-start">transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-1 border-gray-300 bg-gray-50 p-4 text-sm hover:bg-gray-100" v-for="(enrollment, index) in enrollments.data">
                        <td scope="col" class="p-4 font-medium">{{ index + 1 }}</td>
                        <td>{{ enrollment.name.slice(0, 30) }}</td>
                        <td>{{ enrollment.email }}</td>
                        <td>{{ enrollment.date }}</td>
                        <td v-if="page.props.courseType== 'paid'" class="text-green-500" :class="{ 'text-red-500': !enrollment.paid }">{{ enrollment.paid ? 'paid' : 'no' }}</td>

                        <td v-if="page.props.courseType== 'paid'">
                            <button
                                @click="openTransactionModal(enrollment.transaction) "
                                class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900 focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 focus:outline-none"
                            >
                                Transaction
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- modal -->
            <Modal v-if="transaction" :show="showModal" title="Transaction" @close="showModal = false">
                <div class="w-full flex justify-center gap-5">
                        <div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Customer Name:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.CustomerName }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Invoice Id:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.InvoiceId }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Invoice Value:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.InvoiceValue }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Payment Gateway:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.PaymentGateway }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Customer Phone:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.CustomerMobile }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Invoice Status:</p>
                                    <p class="text-lg font-medium text-green-500 ">{{ transaction.InvoiceStatus }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Paid Currency:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.PaidCurrency }}</p>
                            </div>
                            <div class="my-2 flex gap-3">
                                    <p class="text-lg font-semibold">Card Number:</p>
                                    <p class="text-lg font-medium text-gray-400">{{ transaction.CardNumber }}</p>
                            </div>
                        </div>
                </div>
            </Modal>
        </div>
    </AppLayout>
</template>
