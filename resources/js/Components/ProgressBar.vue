<template>
    <div v-if="status == 'available'" class="flex justify-center items-center">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 25%"></div>
        </div>
        <span class="ml-4 text-blue-600">25%</span>
    </div>
    <div v-if="status == 'endorsing'" class="flex justify-center items-center">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-orange-600 h-2.5 rounded-full" style="width: 50%"></div>
        </div>
        <span class="ml-4 text-orange-600">50%</span>
    </div>
    <div v-if="status == 'voting'" class="flex justify-center items-center">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-yellow-600 h-2.5 rounded-full" style="width: 75%"></div>
        </div>
        <span class="ml-4 text-yellow-600">75%</span>
    </div>
    <div v-if="status == 'approved' && !hasResolution" class="flex justify-center items-center">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-green-600 h-2.5 rounded-full" style="width: 90%"></div>
        </div>
        <span class="ml-4 text-green-600">90%</span>
    </div>
    <div v-if="status == 'approved' && hasResolution" class="flex justify-center items-center">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%"></div>
        </div>
        <span class="ml-4 text-green-600">100%</span>
    </div>
</template>

<script>
import { ref } from 'vue'

export default {
    components: {
    },
    props:['status','hasResolution'],
}
</script>