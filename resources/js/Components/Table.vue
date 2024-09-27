<template>
    <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="w-full divide-y divide-gray-300">
            <thead class="bg-blue-600">
                <slot name="headers"></slot>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <slot name="data"></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
import { ref } from 'vue'

export default {
    components: {
    },
    props:['data'],
    methods: {
    }
}
</script>