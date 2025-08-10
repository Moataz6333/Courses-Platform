<script setup lang="ts">
defineProps({
  iconComponent: {
    type: [Object, Function], // ✅ Accept both component object and function
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
});
</script>

<template>
  <div class="flex items-center gap-4 rounded-xl border p-4 shadow-sm bg-white dark:bg-gray-900">
    <component :is="iconComponent" class="h-6 w-6 text-blue-500" />
    <div>
      <p class="text-sm text-gray-500 dark:text-gray-400">{{ label }}</p>
      <p class="text-xl font-semibold">{{ value }}</p>
    </div>
  </div>
</template>
