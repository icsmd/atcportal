<template>
    <app-layout title="Dashboard">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div class="group relative bg-blue-600 text-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                <dt>
                    <div class="absolute">
                        <UsersIcon class="transition duration-0 group-hover:scale-110 group-hover:duration-300 h-12 w-12 text-gray-700" aria-hidden="true" />
                    </div>
                    <p class="ml-16  font-medium truncate">New Applications</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p class="text-2xl font-semibold">
                        {{available_count}}
                    </p>
                    <div class="absolute bottom-0 inset-x-0 bg-blue-700 px-4 py-4 sm:px-6">
                        <div class="">
                            <Link :href="route('applications')" class="font-medium text-white">
                                View all
                            </Link>
                        </div>
                    </div>
                </dd>
            </div>
            <div class="group relative bg-emerald-600 text-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                <dt>
                    <div class="absolute">
                        <DocumentTextIcon class="transition duration-0 group-hover:scale-110 group-hover:duration-300 h-12 w-12 text-gray-700" aria-hidden="true" />
                    </div>
                    <p class="ml-16  font-medium truncate">For Review</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p class="text-2xl font-semibold">
                        {{review_count}}
                    </p>
                    <div class="absolute bottom-0 inset-x-0 bg-emerald-700 px-4 py-4 sm:px-6">
                        <div class="">
                            <Link :href="route('applications')" class="font-medium text-white">
                                View all
                            </Link>
                        </div>
                    </div>
                </dd>
            </div>
            <div class="group relative bg-yellow-600 text-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                <dt>
                    <div class="absolute">
                        <SaveIcon class="transition duration-0 group-hover:scale-110 group-hover:duration-300 h-12 w-12 text-gray-700" aria-hidden="true" />
                    </div>
                    <p class="ml-16  font-medium truncate">For Votation</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p class="text-2xl font-semibold">
                        {{vote_count}}
                    </p>
                    <div class="absolute bottom-0 inset-x-0 bg-yellow-700 px-4 py-4 sm:px-6">
                        <div class="">
                            <Link :href="route('applications')" class="font-medium text-white">
                                View all
                            </Link>
                        </div>
                    </div>
                </dd>
            </div>
            <div class="group relative bg-red-600 text-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                <dt>
                    <div class="absolute">
                        <ArchiveIcon class="transition duration-0 group-hover:scale-110 group-hover:duration-300 h-12 w-12 text-gray-700" aria-hidden="true" />
                    </div>
                    <p class="ml-16  font-medium truncate">Archive</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p class="text-2xl font-semibold">
                        {{archive_count}}
                    </p>
                    <div class="absolute bottom-0 inset-x-0 bg-red-700 px-4 py-4 sm:px-6">
                        <div class="">
                            <Link :href="route('applications.archive')" class="font-medium text-white">
                                View all
                            </Link>
                        </div>
                    </div>
                </dd>
            </div>
        </div>
        <div class="mt-6 sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-extrabold text-gray-900">Application Status</h1>
            </div>
        </div>
        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="w-full divide-y divide-gray-300">
                <thead class="bg-blue-600">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left  font-semibold text-white sm:pl-6">Application Number</th>
                        <th scope="col" class="px-3 py-3.5 text-left  font-semibold text-white">Date/Time of Arrest</th>
                        <th scope="col" class="px-3 py-3.5 text-left  font-semibold text-white">Time Left</th>
                        <th scope="col" class="px-3 py-3.5 text-left  font-semibold text-white">Progress</th>
                        <th scope="col" class="px-3 py-3.5 text-left  font-semibold text-white">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="application in applications.data">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3  font-medium text-gray-900 sm:pl-6">
                            <Link :href="route('applications.show',{application:application.id})" class="underline">{{application.control_number}}</Link>
                            <span v-if="application.is_extension" class="badge badge-danger ml-2">Extend</span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4  text-gray-500">{{application.when}}</td>
                        <td class="whitespace-nowrap px-3 py-4  text-gray-500">
                            <vue-countdown :auto-start="!application.hasResolution && application.status_data != 'expired' && application.status_data != 'disapproved'" :time="application.application_expiration" v-slot="{ days, hours, minutes, seconds }">
                                <div class="flex space-x-12">
                                    <div class="justify-left  font-bold">
                                        {{ days }}
                                    </div>
                                    <div class="justify-left  font-bold">
                                        {{ hours }}
                                    </div>
                                    <div class="justify-left  font-bold">
                                        {{ minutes }}
                                    </div>
                                </div>
                                <div class="flex space-x-4">
                                    <div class="justify-left  font-bold">
                                        days
                                    </div>
                                    <div class="justify-left  font-bold">
                                        hours
                                    </div>
                                    <div class="justify-left  font-bold">
                                        minutes
                                    </div>
                                </div>
                            </vue-countdown>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4  text-gray-500">
                            <ProgressBar :status="application.status" :hasResolution="application.hasResolution" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-4  text-gray-500" v-html="application.status_label"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import VueCountdown from '@chenfengyuan/vue-countdown'
    import { Link } from '@inertiajs/vue3'
    import ProgressBar from '@/Components/ProgressBar.vue'

    import { DocumentTextIcon, SaveIcon, UsersIcon, ArchiveIcon } from '@heroicons/vue/outline'

    export default defineComponent({
        components: {
            AppLayout,
            ArchiveIcon,
            DocumentTextIcon,
            Link,
            ProgressBar,
            UsersIcon,
            SaveIcon,
            VueCountdown
        },
        props:['applications','available_count','review_count','vote_count','archive_count']
    })
</script>
