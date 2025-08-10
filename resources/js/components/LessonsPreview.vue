<script setup>
import Thumbnail from './Thumbnail.vue';
const props = defineProps({
    lessons: {
        type: Object,
    },
});
const lessons = props.lessons;
const Appearance = localStorage.getItem('appearance');
</script>
<template>
    <div>
        <div v-for="lesson in lessons" class="my-2 w-full rounded-md border-1  p-3 shadow-md"
         :class="[Appearance=='dark'?'bg-gray-300' : 'bg-white']"
        >
                <h2 class="text-xl text-black font-medium">{{ lesson.title }}</h2>
                <p class="text-sm text-gray-500">{{ lesson.description }}</p>
                 <div v-if="lesson.media" class="mt-4 rounded border bg-white p-3 shadow">
                                    <template v-if="lesson.mediaType == 'pdf'">
                                        <iframe :src="lesson.media" width="100%" height="400px" class="rounded"></iframe>
                                    </template>
                                    <template v-else-if="lesson.mediaType == 'video'">
                                        <video controls class="w-full rounded" :poster="lesson.thumbnail">
                                            <source :src="lesson.media" type="video/mp4"  />
                                            Your browser does not support the video tag.
                                        </video>
                                    </template>
                                    <template v-else>
                                        <img :src="lesson.media" class="w-full max-w-md rounded" alt="Uploaded media" />
                                    </template>
                                
                                </div>
        </div>
    </div>
</template>
