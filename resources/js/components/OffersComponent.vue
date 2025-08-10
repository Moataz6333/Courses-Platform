<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import Modal from '@/components/Modal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const props = defineProps({
    offers: {
        type: Object,
    },
    course: {
        type: Object,
    },
});
const showModal = ref(false);
// edit
const EditForm = useForm({
    course_id:props.course.id,
    offer_id: '',
    value: '',
    end_at: '',
    code: '',
    isPublic: '',
});
const OpenOfferModal = function (offer) {
    EditForm.offer_id = offer.id;
    EditForm.value = offer.value;
    EditForm.end_at = offer.end_at;
    EditForm.code = offer.code;
    EditForm.isPublic = offer.isPublic ? true: false;
    showModal.value = true;
};
function toggleEditPublic() {
    EditForm.isPublic = !EditForm.isPublic;
    EditForm.code = '';
}
// update
function updateOffer() {
    EditForm.put(route('offers.update', EditForm.offer_id), {
        onSuccess: () => {
            showModal.value = false;
            EditForm.reset();
        },
    });
}

// delete
const deleteForm = useForm();
const deleteoffer = function (id) {
    if(confirm('Are you sure you want to delete this offer?')){
        deleteForm.delete(route('offers.destroy', id));

    }
};

</script>

<template>
    <div class="my-3 p-4">
        <table v-if="offers" class="w-full overflow-hidden rounded-md border-1 border-gray-200">
            <thead class="rounded-t-md bg-gray-900 p-2 text-white">
                <tr>
                    <th class="p-3 text-start">#</th>
                    <th class="text-start">value</th>
                    <th class="text-start">ends at</th>
                    <th class="text-start">is Public</th>
                    <th class="text-start">price</th>
                    <th class="text-start">with offer</th>
                    <th class="text-start">code</th>
                    <th class="text-start">edit</th>
                    <th class="text-start">delete</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-1 border-gray-300 bg-gray-50 p-4 text-sm hover:bg-gray-100" v-for="(offer, index) in props.offers.data">
                    <td scope="col" class="p-4 font-medium">{{ index + 1 }}</td>
                    <td>-{{ offer.value }} %</td>
                    <td>{{ offer.end_at ? offer.end_at : 'Opened' }}</td>
                    <td>
                        {{ offer.isPublic ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        {{ course.price }}
                    </td>
                    <td class="text-green-600">
                        {{ course.price - (course.price * offer.value) / 100 }}
                    </td>
                    <td>
                        {{ offer.code ? offer.code : '-' }}
                    </td>
                    <td>
                        <button
                            @click="OpenOfferModal(offer)"
                            class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:outline-none"
                        >
                            Edit
                        </button>
                    </td>
                    <td>
                        <button
                            @click="deleteoffer(offer.id)"
                            class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                            type="button"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- modal -->
        <Modal :show="showModal" title="Offer" @close="showModal = false">
            <div class="my-4 flex w-full items-center justify-between rounded-md p-4">
                <form @submit.prevent="updateOffer" class="w-full">
                    <div class="flex gap-3">
                        <div class="my-3 w-3/4">
                            <label for="title" class="text-xl">Offer Value</label>
                            <input
                                v-model="EditForm.value"
                                class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                placeholder="offerple : 50 => -50% of course price"
                                type="text"
                                name="value"
                                id="value"
                                required
                            />
                            <InputError :message="EditForm.errors.value" class="mt-2" />
                        </div>
                        <div class="my-3 w-1/4">
                            <label for="price" class="text-xl">Ends at (optional)</label>
                            <input
                                v-model="EditForm.end_at"
                                class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                type="date"
                                name="end_at"
                                id="end_at"
                            />
                            <InputError :message="EditForm.errors.end_at" class="mt-2" />
                        </div>
                    </div>
                    <div class="my-3 flex items-end gap-3">
                        <div class="w-1/4">
                            <label for="code" class="text-xl">Code</label>
                            <input
                                v-model="EditForm.code"
                                :disabled="EditForm.isPublic"
                                class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5 text-gray-900 ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                :class="{ 'bg-gray-300 placeholder:text-gray-50': EditForm.isPublic }"
                                placeholder="ex: #54c8h"
                                type="text"
                                name="code"
                                id="code"
                            />
                            <InputError :message="EditForm.errors.code" class="mt-2" />
                        </div>
                        <div class="flex h-full items-end gap-2.5">
                            <input
                                type="checkbox"
                                v-model="EditForm.isPublic"
                                :checked="EditForm.isPublic"
                                name="isPublic"
                                id="isPublic"
                                @click="toggleEditPublic"
                            />
                            <label for="period" class="text-sm">Public </label>
                        </div>
                    </div>

                    <div class="my-3 flex w-full justify-end">
                        <button
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                            type="submit"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>
