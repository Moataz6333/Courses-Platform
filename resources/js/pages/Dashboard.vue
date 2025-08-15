<script setup lang="ts">
import BarChart from '@/components/BarChart.vue';
import DashboardCard from '@/components/DashboardCard.vue';
import LineChart from '@/components/LineChart.vue';
import PieChart from '@/components/PieChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { BookOpen, PercentCircle, Users } from 'lucide-vue-next';
import { computed, onMounted } from 'vue';

// ✅ Get raw props
const page = usePage();
const props = page.props;

// ✅ Extract and cast props
const stats = computed(
    () =>
        props.stats as {
            total_students: number;
            total_courses: number;
            average_course_vote: number;
        },
);

const courseEnrollments = computed(() => props.course_enrollments as { course: string; students: number }[]);
const enrollmentChartData = computed(() => ({
    labels: courseEnrollments.value.map((c) => c.course.slice(0, 12)),
    datasets: [
        {
            label: 'Enrollments',
            backgroundColor: '#3B82F6',
            data: courseEnrollments.value.map((c) => c.students),
            borderWidth: 2,
            borderRadius: 6,
        },
    ],
}));
const enrollemnts_dates = computed(() => props.enrollemnts_dates as { year: string; month: string; count: number }[]);
const enrollemnts_datesData = computed(() => ({
    labels: enrollemnts_dates.value.map((c) => c.month + '/' + c.year),
    datasets: [
        {
            label: 'Enrollments',
            backgroundColor: '#3B82F6',
            data: enrollemnts_dates.value.map((c) => c.count),
        },
    ],
}));
const students_gender = computed(() => props.students_gender as { gender: string; count: number }[]);
const students_gender_data = computed(() => ({
    labels: students_gender.value.map((c) => c.gender),
    datasets: [
        {
            label: 'Gender',
            backgroundColor: ['rgb(54, 162, 235)', 'rgb(255, 99, 132)'],
            hoverOffset: 4,
            data: students_gender.value.map((c) => c.count),
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
        <div class="flex w-full flex-col gap-6 p-4">
            <!-- ✅ Summary Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <DashboardCard icon="Users" :iconComponent="Users" label="Students" :value="stats.total_students" />
                <DashboardCard icon="BookOpen" :iconComponent="BookOpen" label="Courses" :value="stats.total_courses" />
                <DashboardCard icon="PercentCircle" :iconComponent="PercentCircle" label="Avg Rate" :value="`${stats.average_course_vote}/5`" />
            </div>

            <div class="my-3 flex max-w-full flex-wrap justify-evenly gap-3">
                <!-- 📊 Course Enrollments Chart -->
                <div class="rounded-xl border bg-white p-3 shadow dark:bg-gray-900">
                    <h2 class="mb-4 text-lg font-semibold">Course Enrollments</h2>
                    <BarChart :data="enrollmentChartData" />
                </div>
                <!-- 📊enrollemnts_datesData -->
                <div class="rounded-xl border bg-white p-3 shadow dark:bg-gray-900">
                    <h2 class="mb-4 text-lg font-semibold">Enrollments Per Month</h2>
                    <LineChart :data="enrollemnts_datesData" />
                </div>
                <!-- 📊 Students gender -->
                <div class="rounded-xl border bg-white p-3 shadow dark:bg-gray-900">
                    <h2 class="mb-4 text-lg font-semibold">Students Gender</h2>
                    <PieChart :data="students_gender_data" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
