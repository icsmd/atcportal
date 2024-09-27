<template>
    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <div class="p-4 bg-red-500 text-white font-extrabold text-lg">
            Resolution 
        </div>
        <div class="px-4 py-6 sm:px-6">
            <div>
                <a :href="application.data.resolution.file" target="_blank" class="inline-flex text-blue-600 text-lg no-underline hover:underline cursor-pointer">Final Resolution
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                </a>
            </div>
            <div v-if="application.data.resolution.attachments.length != 0" class="mt-2">
                <label class="block font-bold text-lg">Attachments: </label>
                <div v-for="(document,index) in application.data.resolution.attachments">
                    <a :href="document.original_url" target="_blank" class="inline-flex text-blue-600 text-lg no-underline hover:underline cursor-pointer">{{document.name + '.' + document.extension}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                      </svg>
                    </a>
                </div>
            </div>
        </div>
        <div v-if="application.data.extension.file" class="px-4 py-6 sm:px-6">
            <div>
                <label for="remarks" class="form-label"> Extension</label>
                <a :href="application.data.extension.file" target="_blank" class="inline-flex text-blue-600 text-lg no-underline hover:underline cursor-pointer">Final Resolution Extension
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                </a>
            </div>
            <div v-if="application.data.extension.attachments.length != 0" class="mt-2">
                <label class="block font-bold text-lg">Extension Attachments: </label>
                <div v-for="(document,index) in application.data.extension.attachments">
                    <a :href="document.original_url" target="_blank" class="inline-flex text-blue-600 text-lg no-underline hover:underline cursor-pointer">{{document.name + '.' + document.extension}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                      </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        components: {
        },
        props: ['application'],
        data() {
            return {
            }
        },
        methods: {
        },
    })
</script>