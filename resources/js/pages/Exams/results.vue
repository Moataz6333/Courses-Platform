<script setup>
import BackHeader from '@/components/BackHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage ,Link } from '@inertiajs/vue3';
import { Pin, TestTubes } from 'lucide-vue-next';
import { ref, watch ,onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import Pagination from '@/components/Pagination.vue';
import { router } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js'

const toast = useToast();
const page = usePage();
let pageNumber=ref("1");
const total = page.props.total;
const info = page.props.info;
const exam = page.props.exam.data;
const results = page.props.results.data;

Chart.register(...registerables)

defineProps({
    exam:{
        type: Object,
        required:true
    },
    results:{
        type: Object,
    },
    grades:{
        type: Array,
    },
    counts:{
        type: Array,
    },
    info:{
        type: Object,
    },
    total:{
        type:Number,
        required:true
    }

});

const updatedPageNumber=(link)=>{
       pageNumber.value=link.url.split("=")[1];
          let url =new URL(route("exam.results",exam.id));
          url.searchParams.append("page",pageNumber.value);
        router.visit(url,{
            preserveScroll:true,
        });
}
const  gradeClass=function(grade) {
      switch (grade) {
        case 'A': return 'text-green-700 font-bold';
        case 'A-': return 'text-green-600 font-semibold';
        case 'B+': return 'text-blue-600';
        case 'B': return 'text-blue-500';
        case 'B-': return 'text-blue-400';
        case 'C+': return 'text-yellow-600';
        case 'C': return 'text-yellow-500';
        case 'C-': return 'text-yellow-400';
        case 'F': return 'text-red-600 font-bold';
        default: return 'text-gray-600';
      }
    };


   onMounted(() => {
  const ctx = document.getElementById('gradesChart').getContext('2d')

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: page.props.grades,
      datasets: [{
        label: 'Number of Students',
        data: page.props.counts,
        backgroundColor: '#3b82f6'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 1
        }
      }
    }
  })
})
    


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
    <Head title="Results" />
    <AppLayout>
        <div class="container">
            <BackHeader back-route="exams.index" :param="exam.course.id" :text="`Results of : ${exam.title}`" />

            <div class="my-3 p-3">
                 <div class="grid grid-cols-2 gap-4 p-3 w-full">
                    <div class="p-3">
                        <div class="my-2 flex gap-1">
                            <h3 class="text-xl font-medium">Success percentage : </h3>
                            <p class="text-xl text-green-500">{{ info['success'] }} %</p>
                        </div>
                        <div class="my-2 flex gap-1">
                            <h3 class="text-xl font-medium">Fail percentage : </h3>
                            <p class="text-md text-red-500">{{ info['fail']}} %</p>
                        </div>
                        <div class="my-2 flex gap-1">
                            <h3 class="text-xl font-medium">Average Time : </h3>
                            <p class="text-md text-blue-500">{{ info['aveTimeTaken'] }} min</p>
                        </div>
                        <div class="my-2 flex gap-1 flex-wrap">
                            <h3 class="text-xl font-medium">Conclusion : </h3>
                            <p class="text-md ">{{ info['conclusion'] }}</p>
                        </div>
                    </div>
                 <div class="relative w-full h-full">
               <canvas id="gradesChart" class="w-full h-full"></canvas>
                        </div>
                 </div>
                    <hr>
                 <Pagination :data="page.props.results" :updatedPageNumber="updatedPageNumber" />
                 <table v-if="results" class="w-full rounded-md border-1 border-gray-200 overflow-hidden">
                <thead class="text-white bg-gray-900 p-2 rounded-t-md">
                    <tr>
                        <th class="text-start p-3">#</th>
                        <th class="text-start ">name</th>
                        <th class="text-start">email</th>
                        <th class="text-start">correct (total:{{ total }})</th>
                        <th class="text-start">grade</th>
                        <th class="text-start">Submittd At</th>
                        <th class="text-start">status</th>
                    </tr>
                    </thead>
                <tbody>
                   <tr v-for="(result,index) in results" class="border-1  border-gray-300 bg-gray-50 p-4 text-black text-sm hover:bg-gray-100">
                    <td scope="col" class="p-4 font-medium">{{ index+1 }}</td>
                    <td>{{ result.name }}</td>
                    <td>{{ result.email }}</td>
                    <td>{{ result.correct }}</td>
                    <td :class="gradeClass(result.grade)">{{ result.grade }}</td>
                    <td>{{ result.created_at }}</td>
                    <td v-if="result.status== 'done'" class="text-green-500" :class="{ 'text-red-500': result.status=='pending' }">{{ result.status }}</td>
                   </tr>
                </tbody>

            </table>
               
            </div>
        </div>
    </AppLayout>
</template>
