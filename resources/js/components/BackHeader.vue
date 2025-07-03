<script setup>
import {  Link } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps({
  text: {
    type: String,
    required: true
  },
  backRoute: {
    type: String,
    required: true
  },
  param:{
    type:Number
  }
})

</script>
<template>
    <div class="header flex w-full items-center gap-3 rounded-b-2xl border-b-2 p-3 px-6">
                <Link :href="route(props.backRoute,props.param)" class="rounded-md bg-gray-800 p-2 text-white hover:bg-gray-950">
                    <ArrowBigLeft />
                </Link>
                <h1 class="text-bold text-2xl font-medium text-gray-950">{{props.text}} </h1>
            </div>
</template>