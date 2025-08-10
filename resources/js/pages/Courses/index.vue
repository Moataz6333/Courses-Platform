<script setup >
import AppLayout from '@/layouts/AppLayout.vue';
import { Head ,Link ,usePage} from '@inertiajs/vue3';
import CourcesTable from '@/components/CourcesTable.vue';
import { Pin } from 'lucide-vue-next';
const page=usePage();
defineProps({
    courses:{
        type:Object
    }
});
</script>
<template>
    <Head title="Courses" />
    <AppLayout >

        <div class="container">
            <div class="header p-3 border-b-2 rounded-b-2xl flex w-full justify-between px-6 items-center">
                <h1 class="text-3xl  text-bold  font-medium">Courses</h1>
                 <Link
                            :href="route('courses.create')"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none sm:w-auto"
                        >
                            Add Course
                     </Link>
            </div>

            <CourcesTable :courses="page.props.courses" />

        </div>

    </AppLayout>

</template>