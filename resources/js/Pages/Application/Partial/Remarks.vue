<template>
    <div class="px-4 pb-4">
        <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <div class="p-4 bg-cyan-500 text-white font-extrabold ">
                {{title}}
            </div>
            <div class="p-4">
                <div>
                    <div>
                        <label class="block font-bold ">Date: </label>
                        <span>{{date}}</span>
                    </div>
                    <div v-if="remarks" class="mt-2">
                        <label class="block font-bold ">Remarks: </label>
                        <span>{{remarks}}</span>
                    </div>
                    <div v-if="documents.length != 0" class="mt-2">
                        <label class="block font-bold ">Attachments: </label>
                        <div v-for="(document,index) in documents">
                            <a :href="document.original_url" target="_blank" class="inline-flex text-blue-600  no-underline hover:underline cursor-pointer">{{document.name + '.' + document.extension}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                              </svg>
                            </a>
                        </div>
                    </div>
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
        data() {
            return {
            }
        },
        props:['date','remarks','documents','title'],
        methods: {
        },

    })
</script>
