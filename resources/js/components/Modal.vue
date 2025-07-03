<script setup>
defineProps({
  show: Boolean,
  title: String
});
const emit = defineEmits(['close']);
const close = () => emit('close');
</script>
<template>
  <div
  v-if="show"
  class="fixed inset-0 bg-black opacity-50  flex items-center justify-center z-49"
  ></div>
  <div
    v-if="show"
    class="fixed inset-0  flex items-center justify-center z-50"
    
  >
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-fit w-full">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ title }}</h2>
        <button @click="close" class="text-gray-500 hover:text-black cursor-pointer">&times;</button>
      </div>
      <div class="text-gray-700">
        <slot />
      </div>
      <div class="mt-4 text-right">
        <button @click="close" class="bg-blue-500 cursor-pointer text-white px-4 py-2 rounded hover:bg-blue-600">
          Close
        </button>
      </div>
    </div>
  </div>
</template>


