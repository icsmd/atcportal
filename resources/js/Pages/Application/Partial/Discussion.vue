<template>
    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <div class="p-4 bg-red-500 text-white font-extrabold ">
            Discussion
        </div>
        <div class="p-4 flex justify-center">
            <span class="text-3xl font-bold">Endorsed : {{ numberEndorse }}/4</span>
        </div>
        <div class="px-4 py-6 sm:px-6">
            <ul role="list" class="space-y-8" v-if="comments.length !== 0">
                <li v-for="comment in comments" :key="comment.name">
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="comment.image" :alt="comment.name" />
                        </div>
                        <div>
                            <div class="">
                                <span class="font-bold text-gray-900">{{comment.name}}</span>
                            </div>
                            <div class="mt-1  text-gray-700">
                                <p>{{comment.message}}</p>
                            </div>
                            <div class="mt-2  space-x-2">
                                <span class="text-gray-500 font-medium">{{comment.created_at}}</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-else class="flex justify-center">
                <span class="">No discussions yet</span>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-6 sm:px-6" v-if="status === 'endorsing' && canComment">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                </div>
                <div class="min-w-0 flex-1">
                    <div>
                        <label for="comment" class="sr-only">Comment</label>
                        <textarea id="comment" name="comment" rows="3" :class="changeClassIfError(form.errors.body)" placeholder="Leave a comment..." v-model="form.body" />
                        <jet-input-error :message="form.errors.body" class="mt-2" />
                    </div>
                    <div class="mt-3 flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent  font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" @click="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Comment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetInputError from '@/Jetstream/InputError.vue'

    export default defineComponent({
        components: {
            JetInputError,
        },
        data() {
            return {
                form: this.$inertia.form({
                    body: '',
                }),
            }
        },
        props: ['status','comments','canComment','id', 'numberEndorse'],
        methods: {
            submit() {
                this.form.post(route('applications.comment',{application:this.id}),{
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                })
            },
            changeClassIfError(errorMessage) {
                return {
                    'block': true,
                    'w-full': true,
                    'focus:outline-none': true,
                    'sm:': true,
                    'rounded-md': true,
                    'shadow-sm': true,
                    //Normal State
                    'focus:ring-indigo-500': errorMessage == undefined,
                    'focus:border-indigo-500': errorMessage == undefined,
                    'border-gray-300': errorMessage == undefined,
                    //Error State
                    'text-red-900': errorMessage,
                    'placeholder-red-300': errorMessage,
                    'border-red-300': errorMessage,
                    'focus:ring-red-500': errorMessage,
                    'focus:border-red-500': errorMessage,
                }
            },
        },

    })
</script>
