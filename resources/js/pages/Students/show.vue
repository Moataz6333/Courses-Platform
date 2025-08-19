<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head,usePage } from '@inertiajs/vue3';
import BackHeader from '@/components/BackHeader.vue';
import Heading from '@/components/Heading.vue';
import EnrollmentCard from '@/components/EnrollmentCard.vue';
const page=usePage();
defineProps({
    student:{
        type:Object,
        required:true
    },
    enrollments:{
        type:Object,
        required:true
    },
    results:{
        type:Object,
        required:true
    },
});
const student=page.props.student.data;
const enrollments=page.props.enrollments.data;
const results=page.props.results.data;

</script>
<template>
    <AppLayout>
         <Head title="Student" />
        <BackHeader back-route="students.index" :text="student.name" />
         <!-- student details -->
            <div class="my-3 w-full p-3 flex gap-4">
                    <!-- profile -->
                     <div class="w-1/4">
                        <img :src="student.profile" alt="profile" class="w-full h-full">
                     </div>
                     <!-- text -->
                      <div class="w-full">
                        <h2 class="text-xl my-2.5"><strong>Name:</strong> {{ student.name }}</h2>
                        <h2 class="text-xl my-2.5"><strong>Email:</strong> {{ student.email }}</h2>
                        <h2 class="text-xl my-2.5"><strong>Phone:</strong> {{ student.phone }}</h2>
                        <h2 class="text-xl my-2.5"><strong>Birthdate:</strong> {{ student.birthdate }}</h2>
                      </div>
            </div>
            <hr class="bg-gray-500 px-3">
            <!-- student enrollemnt -->
            <div class="w-full p-3 my-3">
                <Heading title="Enrollments"/>
                <div class="my-3" v-for="enrollment in enrollments">
                    <EnrollmentCard :enrollment=enrollment />
                </div>
            </div>
            <hr class="bg-gray-500 px-3">
            <!-- student exams -->
            <div class="w-full p-3 my-3">
                <Heading title="Results"/>
                <div class="my-3" >
                    
                </div>
            </div>

    </AppLayout>

</template>