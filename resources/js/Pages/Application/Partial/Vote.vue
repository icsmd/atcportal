<template>
    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <div class="p-4 bg-yellow-500 text-white font-extrabold ">
            Vote
        </div>
        <div class="px-4 py-6 sm:px-6">
            <div class="flex justify-center">
                <span class="text-3xl font-bold">Votes Casted: {{numberVoted}}/{{maxVote}}</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex justify-center">
                    <div class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-green-700 h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                        </svg>
                        <span class="text-2xl font-bold ml-4">
                            {{numberOfApprove}}
                        </span>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-red-700 h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                        </svg>
                        <span class="text-2xl font-bold ml-4">
                            {{numberOfDisapprove}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="votes.length == 0" class="px-4 py-6 sm:px-6">
            <div class="flex justify-center">
                <span class="">No votes yet</span>
            </div>
        </div>
        <div v-else class="px-4 py-6 sm:px-6">
            <ul role="list" class="space-y-8">
                <li v-for="vote in votes" :key="vote.name">
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="vote.image" :alt="vote.name" />
                        </div>
                        <div>
                            <div class="">
                                <span class="font-bold text-gray-900">{{vote.name}}</span>
                            </div>
                            <div class="mt-1  text-gray-700">
                                <p>{{vote.message}}</p>
                            </div>
                            <div class="mt-2  space-x-2">
                                <span class="text-gray-500 font-medium">{{vote.created_at}}</span>
                                {{ ' ' }}
                                <span class="text-gray-500 font-medium">&middot;</span>
                                {{ ' ' }}
                                <span v-if="vote.status == 'approved'" class="badge badge-success">APPROVED</span>
                                <span v-else class="badge badge-danger">DISAPPROVED</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="bg-gray-50 px-4 py-6 sm:px-6" v-if="status == 'voting' && canVote">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                </div>
                <div class="min-w-0 flex-1">
                    <div>
                        <label for="message" class="sr-only">Vote</label>
                        <textarea id="message" name="message" rows="3" :class="changeClassIfError(form.errors.message)" placeholder="Leave a message..." v-model="form.message" />
                        <jet-input-error :message="form.errors.message" class="mt-2" />
                    </div>
                    <div class="mt-3 flex items-center justify-end">
                        <button type="submit" class="btn btn-success" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit('approved')">Approve</button>
                        <button type="submit" class="btn btn-danger ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit('disapproved')">Disapprove</button>
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
                    message: '',
                    status: ''
                }),
            }
        },
        props: ['status','votes','canVote','id','maxVote'],
        methods: {
            submit(decision) {
                this.form.status = decision

                this.form.post(route('applications.vote',{application:this.id}),{
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
        computed: {
            numberVoted() {
                return this.votes ? this.votes.length : 0
            },
            numberOfApprove() {
                return this.votes.filter((vote) => { return vote.status == 'approved' }).length
            },
            numberOfDisapprove() {
                return this.votes.filter((vote) => { return vote.status == 'disapproved' }).length
            },
        }

    })
</script>
