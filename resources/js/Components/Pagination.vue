<template>
    <div v-if="dataSet.meta.last_page != 1" class="bg-white px-4 pt-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <Link :href="dataSet.links.prev" class="relative inline-flex items-center px-4 py-2 border border-gray-300 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Previous </Link>
            <Link :href="dataSet.links.next" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Next </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-gray-700">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{dataSet.meta.from}}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{dataSet.meta.to}}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{dataSet.meta.total}}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <template v-for="link in dataSet.meta.links" :key="link.label">
                        <Link v-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')" :href="link.url" class="inline-flex items-center px-4 py-2 border font-medium" :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50']"> {{link.label}} </Link>

                        <Link v-if="link.url && link.label.includes('Previous')" :href="link.url" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white font-medium text-gray-500 hover:bg-gray-50"> <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" /> </Link>

                        <Link v-if="link.url && link.label.includes('Next')" :href="link.url" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white font-medium text-gray-500 hover:bg-gray-50"> <ChevronRightIcon class="h-5 w-5" aria-hidden="true" /> </Link>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    ChevronLeftIcon,
    ChevronRightIcon,
} from '@heroicons/vue/solid'

export default {
    components: {
        ChevronLeftIcon,
        ChevronRightIcon,
        Link
    },
    props:['dataSet'],
    methods: {

    }
}
</script>
