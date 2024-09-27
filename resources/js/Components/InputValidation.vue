<template>
    <div>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input :type="type" :id="id" :class="changeClass()" :placeholder="placeholderValue" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" v-if="errorMessage">
                <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
            </div>
        </div>
        <p class="mt-2 text-red-600">{{errorMessage}}</p>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { ExclamationCircleIcon } from '@heroicons/vue/solid'

    export default defineComponent({
        components: {
            ExclamationCircleIcon,
        },
        props: ['modelValue','placeholderValue','errorMessage','type','id'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            },
            changeClass() {
                return {
                    'block': true,
                    'w-full': true,
                    'pr-10': true,
                    'focus:outline-none': true,
                    'rounded-md': true,
                    'shadow-sm': true,
                    //Normal State
                    'focus:ring-indigo-500': this.errorMessage == undefined,
                    'focus:border-indigo-500': this.errorMessage == undefined,
                    'border-gray-300': this.errorMessage == undefined,
                    //Error State
                    'text-red-900': this.errorMessage,
                    'placeholder-red-300': this.errorMessage,
                    'border-red-300': this.errorMessage,
                    'focus:ring-red-500': this.errorMessage,
                    'focus:border-red-500': this.errorMessage,
                }
            }
        }
    })
</script>