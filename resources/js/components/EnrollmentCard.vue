<script setup>
import { ref,reactive } from 'vue';
import Modal from '@/components/Modal.vue';
import { Gem } from 'lucide-vue-next';

const props = defineProps({
    enrollment: {
        type: Object,
        required: true,
    },
});
const enrollment = props.enrollment;
const showModal = ref(false);
let transaction = reactive({});
const openTransactionModal=function(tr){
    transaction=tr;
    showModal.value=true;
}
</script>
<template>
    <div class="w-3/4 p-3 rounded border border-gray-300 shadow-md">
        <div class="flex w-full justify-between mb-3">
            <h2 class="font-bold w-fit text-xl flex"><Gem class="mr-2 text-purple-400" /> {{ enrollment.courseName }}</h2>
            <p class="text-green-600 text-md font-semibold">{{ enrollment.courseType }}</p>
        </div>
        <div class="flex w-full justify-between">
            <h2 class="font-semibolf w-fit text-xl"><strong>Since :</strong> {{ enrollment.date }}</h2>
            <p class=" text-md"><strong>Status :</strong> <span v-if="enrollment.paid" class="text-green-600">paid</span>  <span v-else class="text-red-500">not paid</span></p>
            <button
                v-if="enrollment.courseType == 'paid'"
                @click="openTransactionModal(enrollment.transaction)"
                class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-900 focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 focus:outline-none"
            >
                Transaction
            </button>
        </div>
        <!-- modal -->
        <Modal v-if="transaction" :show="showModal" title="Transaction" @close="showModal = false">
            <div class="flex w-full justify-center gap-5">
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
                        <p class="text-lg font-medium text-green-500">{{ transaction.InvoiceStatus }}</p>
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
</template>
