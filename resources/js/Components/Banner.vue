<template>
    <div v-if="show && message " class="rounded-md p-4 mb-4" :class="{ 'bg-green-200': style == 'success', 'bg-red-200': style == 'danger' }">
        <div class="flex">
            <div class="flex-shrink-0">
                <CheckCircleIcon v-if="style == 'success'" class="h-5 w-5 text-green-400" aria-hidden="true" />
                <XCircleIcon v-else class="h-5 w-5 text-red-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <p class="font-medium" :class="{ 'text-green-800': style == 'success', 'text-red-800': style == 'danger' }">{{ message }}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2" :class="{ 'bg-green-50 text-green-500 hover:bg-green-100 focus:ring-offset-green-50 focus:ring-green-600': style == 'success', 'bg-red-50 text-red-500 hover:bg-red-100 focus:ring-offset-red-50 focus:ring-red-600': style == 'danger' }" @click.prevent="show = false">
                        <span class="sr-only">Dismiss</span>
                        <XIcon class="h-5 w-5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { CheckCircleIcon, XCircleIcon, XIcon } from '@heroicons/vue/solid'

    export default defineComponent({
        components: {
            CheckCircleIcon,
            XCircleIcon,
            XIcon,
        },
        data() {
            return {
                show: true,
            }
        },

        computed: {
            style() {
                return this.$page.props.jetstream.flash?.bannerStyle || 'success'
            },

            message() {
                return this.$page.props.jetstream.flash?.banner || ''
            },
        }
    })
</script>
