<script setup>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';
import { Bar } from 'vue-chartjs';

defineProps({
  data: {
    type: Object,
    required: true,
  },
  options: {
    type: Object,
    default: () => ({
      responsive: true,
      maintainAspectRatio: false,
    }),
  },
});

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
</script>

<template>
  <div class="w-full h-[300px]">
    <Bar :data="data" :options="options" />
  </div>
</template>
