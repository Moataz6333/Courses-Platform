<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import DashboardCard from '@/components/DashboardCard.vue';
import BarChart from '@/components/BarChart.vue';
import { onMounted } from 'vue';
import {
  Users,
  BookOpen,
  PercentCircle
} from 'lucide-vue-next';

// ✅ Get raw props
const page = usePage();
const props = page.props;

// ✅ Extract and cast props
const stats = computed(() => props.stats as {
  total_students: number;
  total_courses: number;
  average_course_vote: number;
});

const courseEnrollments = computed(() => props.course_enrollments as { course: string; students: number }[]);
const enrollmentChartData = computed(() => ({
  labels: courseEnrollments.value.map(c => c.course),
  datasets: [
    {
      label: 'Enrollments',
      backgroundColor: '#3B82F6',
      data: courseEnrollments.value.map(c => c.students),
    },
  ],
}));

onMounted(() => {
    const savedAppearance = localStorage.getItem('appearance');
   

});
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="[{ title: 'Dashboard', href: '/dashboard' }]">
    <div class="flex flex-col gap-6 p-4">
      <!-- ✅ Summary Cards -->
      <div class="grid gap-4 md:grid-cols-3">
        <DashboardCard icon="Users" :iconComponent="Users" label="Students" :value="stats.total_students" />
        <DashboardCard icon="BookOpen" :iconComponent="BookOpen" label="Courses" :value="stats.total_courses" />
        <DashboardCard icon="PercentCircle" :iconComponent="PercentCircle" label="Avg Rate" :value="`${stats.average_course_vote}%`" />
      </div>


      <!-- 📊 Course Enrollments Chart -->
      <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow border">
        <h2 class="text-lg font-semibold mb-4">Course Enrollments</h2>
        <BarChart :data="enrollmentChartData" />
      </div>

     
    </div>
  </AppLayout>
</template>
