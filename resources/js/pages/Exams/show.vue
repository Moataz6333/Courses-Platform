<script setup>
import BackHeader from '@/components/BackHeader.vue';
import InputError from '@/components/InputError.vue';
import QuestionsComponent from '@/components/QuestionsComponent.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Check, Trash2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const page = usePage();

const exam = page.props.exam.data;

defineProps({
    exam: {
        type: Object,
        required: true,
    },
    questions: {
        type: Object,
    },
});

const written = ref(false);
let previewUrl = ref('');
const activeOptions = () => {
    form.hasOptions = true;
    written.value = false;
};
const activeWritten = () => {
    form.hasOptions = false;
    written.value = true;
};

const errorMessage = ref('');

const appOption = () => {
    let lastId = form.options[form.options.length - 1].id;
    if (form.options.length == 5) {
        errorMessage.value = 'Max number is 5 options';
    } else {
        form.options.push({
            id: lastId + 1,
            answer: '',
            correct: false,
        });
    }
};
const selectAnswer = (id) => {
    form.options.map((option) => {
        if (option.id == id) {
            option.correct = true;
        } else {
            option.correct = false;
        }
    });
};
const deleteOption = (id) => {
    if (form.options.length - 1 == 1) {
        errorMessage.value = 'the minimum options required is 2';
    } else {
        form.options.splice(id, 1);
    }
};
// form
const form = useForm({
    questionHead: '',
    answer: '',
    points: '',
    media: '',
    hasOptions: true,
    options: [
        {
            id: 1,
            answer: '',
            correct: true,
        },
        {
            id: 2,
            answer: '',
            correct: false,
        },
    ],
});
const createQuestion = () => {
    if(form.questionHead =='' && form.media==''){
        toast.error('Question Head is required !');
        return;
    }
    if (form.hasOptions) {
        let optionsHasAnswer = false;
        form.options.map((option) => {
            if (option.correct == true) {
                optionsHasAnswer = true;
            }
        });
        if (optionsHasAnswer) {
            form.post(route('exams.question', exam.id), {
                onSuccess: () => {
                    form.reset();
                },
            });
        } else {
            errorMessage.value = 'the options must has correct answer';
        }
    } else {
        if (form.answer == '') {
            errorMessage.value = 'the question must has an answer';
        } else {
            form.post(route('exams.question', exam.id), {
                onSuccess: () => {
                    form.reset();
                },
            });
        }
    }
    
};
const handleThumbnailChange = (event) => {
    const file = event.target.files[0];
    previewUrl.value = '';

    if (!file) return;

    form.media = file;
    previewUrl.value = URL.createObjectURL(file);
};
const cancelUpload=()=>{
    form.reset('media');
    previewUrl.value='';
    document.getElementById('myform').reset('media');
   
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
    <Head title="Add-Exam" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="exams.index" :param="exam.course.id" :text="` Exam  : ${exam.title}`" />

            <div class="my-3 p-4">
                <h1 class="text-center text-2xl font-medium">Add Questions</h1>
                <div class="questionForm my-3 mb-4 w-full rounded-md border-1 border-gray-200 p-3 shadow-lg">
                    <form @submit.prevent="createQuestion" id="myform">
                        <!-- question head -->
                        <div class="my-3">
                            <div class="grid w-full grid-cols-5 gap-3">
                                <div class="col-span-3">
                                    <label for="head" class="text-xl">Question Head</label>
                                    <textarea
                                        v-model="form.questionHead"
                                        class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                        placeholder="Head ?"
                                        name="head"
                                        id="head"
                                    ></textarea>
                                </div>
                                <!-- media -->
                                <div class="col-span-1">
                                    <label for="media" class="text-xl">Image (op)</label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleThumbnailChange"
                                        class="mt-2 block w-3/4 rounded-lg border border-gray-300 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700"
                                        id="media"
                                    />
                                    <InputError :message="form.errors.media" class="mt-2" />

                                </div>
                                <div class="col-span-1">
                                    <label for="points" class="text-xl">Points</label>
                                    <input
                                        v-model="form.points"
                                        class="mt-2.5 block w-full rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                        placeholder="Points"
                                        type="text"
                                        name="points"
                                        id="points"
                                        required
                                    />
                                    <InputError :message="form.errors.points" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <!-- Preview -->
                                    <div v-if="previewUrl" class="my-3">
                                        <p class="mb-2 text-sm text-gray-600">Preview:</p>
                                        <img :src="previewUrl" alt="Preview" class="h-40 rounded-md border object-cover shadow" />
                                         <button
                                        @click="cancelUpload"
                                        class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-red-600 p-2 px-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                       
                                        type="button"
                                    >Cancel
                                </button>
                                    </div>
                        <!-- type -->
                        <div class="my-2 flex items-center justify-center gap-2.5">
                            <label for="type" class="text-sm">type : </label>
                            <input type="radio" name="choose" id="choose" @click="activeOptions" checked />
                            <label for="choose">options</label>
                            <input type="radio" name="choose" id="answer" @click="activeWritten" />
                            <label for="choose">written</label>
                        </div>
                        <p v-if="errorMessage != ''" class="text-medium my-2 text-center text-red-500">
                            {{ errorMessage }}
                        </p>
                        <!-- options div -->
                        <div v-if="form.hasOptions && !written" class="my-3 w-full rounded-md border-1 border-gray-100 p-3">
                            <h1 class="text-center text-2xl font-medium">Options</h1>

                            <!-- option div -->

                            <div v-for="(option, index) in form.options" :key="option.id" class="optionDiv flex w-full items-center gap-3">
                                <div class="my-3 flex w-3/4 gap-3 rounded-md border-2 border-gray-200 p-3">
                                    <div class="flex w-full items-center gap-3">
                                        <label for="option">Option {{ index + 1 }} :</label>
                                        <input
                                            v-model="option.answer"
                                            class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:ring-inset sm:text-sm"
                                            placeholder="Answer"
                                            type="text"
                                            name="option"
                                            required
                                        />
                                    </div>

                                    <button
                                        @click="selectAnswer(option.id)"
                                        class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-gray-600 p-2 px-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                                        :class="{
                                            'inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-green-600 p-2 px-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none':
                                                option.correct,
                                        }"
                                        type="button"
                                    >
                                        <Check class="text-sm" />
                                    </button>
                                </div>
                                <button
                                    @click="deleteOption(index)"
                                    class="inline-flex cursor-pointer justify-center rounded-md border border-transparent bg-red-600 p-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                    type="button"
                                >
                                    <Trash2 class="text-sm" />
                                </button>
                            </div>
                            <button
                                type="button"
                                @click="appOption"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                            >
                                Add Option
                            </button>
                            <InputError :message="form.errors.options" class="mt-2" />
                        </div>
                        <!-- written div -->
                        <div
                            v-if="!form.hasOptions && written"
                            class="my-3 flex w-full flex-col items-center justify-center rounded-md border-1 border-gray-100 p-3"
                        >
                            <h1 class="text-center text-2xl font-medium">Written</h1>
                            <textarea
                                v-model="form.answer"
                                class="mt-2.5 block w-3/4 rounded-lg border-0 py-2 pl-5  ring-1 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:text-sm"
                                placeholder="Written Answer"
                                name="answer"
                                id="description"
                                rows="5"
                            ></textarea>
                            <InputError :message="form.errors.answer" class="mt-2" />
                        </div>

                        <!-- submit div -->
                        <div class="my-3 flex w-full justify-end">
                            <button
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                                type="submit"
                            >
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <!-- questions -->
                <QuestionsComponent :questions="page.props.questions" />
            </div>
        </div>
    </AppLayout>
</template>
