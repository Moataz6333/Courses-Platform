<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
const Appearance = localStorage.getItem('appearance');
let logoPath=Appearance=='dark'?
     '/storage/logo-d.png'
  : '/storage/logo-l.png';

</script>

<template>
    <img :src="logoPath" alt="">
</template>
