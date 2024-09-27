<template>
    <app-layout title="Users List">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <input type="text" class="w-full form-input" placeholder="Search..." v-model="search">
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <Link :href="route('users.create')" class="btn btn-primary">Add user</Link>
            </div>
        </div>
        <div class="mt-5 border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <div class="p-4 bg-cyan-500 text-white font-extrabold ">
                Users List
            </div>
            <div class="p-4">
                <ul role="list" class="mt-5 border-t border-gray-200 divide-y divide-gray-200 sm:mt-0 sm:border-t-0">
                    <li v-for="user in users.data">
                        <a class="group block">
                            <div class="flex items-center py-5 px-4 sm:py-6 sm:px-0">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-20 w-20 rounded-full group-hover:opacity-75" :src="user.image" :alt="user.name" />
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                        <div>
                                            <p class=" font-medium text-purple-600 truncate">{{user.name}}</p>
                                            <p class="mt-2 flex items-center  text-gray-500">
                                                <MailIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                                                <span class="truncate">{{user.email}}</span>
                                            </p>
                                            <p class="mt-2  text-gray-900 md:hidden">
                                                {{user.tel}}
                                            </p>
                                            <p v-if="user.active" class="mt-2 flex items-center  text-gray-500 md:hidden">
                                                <CheckCircleIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" aria-hidden="true" />
                                                Active
                                            </p>
                                            <p v-else class="mt-2 flex items-center  text-gray-500 md:hidden">
                                                <XCircleIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-red-400" aria-hidden="true" />
                                                Inactive
                                            </p>
                                        </div>
                                        <div class="hidden md:block">
                                            <div>
                                                <p class=" text-gray-900">
                                                    {{user.tel}}
                                                </p>
                                                <p v-if="user.active" class="mt-2 flex items-center  text-gray-500">
                                                    <CheckCircleIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" aria-hidden="true" />
                                                    Active
                                                </p>
                                                <p v-else class="mt-2 flex items-center  text-gray-500">
                                                    <XCircleIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-red-400" aria-hidden="true" />
                                                    Inactive
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="user.id != $page.props.user.id">
                                    <Popper arrow content="Edit User" :hover="true">
                                        <Link :href="route('users.edit',{user:user.id})" class="btn btn-success p-2 ml-2">
                                            <PencilAltIcon class="h-5 w-5" aria-hidden="true" />
                                        </Link>
                                    </Popper>
                                </div>
                                <div v-else>
                                    <button class="btn btn-success p-2 ml-2 cursor-not-allowed" disabled="disabled">
                                        <PencilAltIcon class="h-5 w-5" aria-hidden="true" />
                                    </button>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>

                <Pagination :dataSet="users"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { Link } from '@inertiajs/vue3'
    import Pagination from '@/Components/Pagination.vue'
    import Popper from 'vue3-popper'
    import { debounce } from 'lodash'

    import {
        CheckCircleIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        XCircleIcon,
        MailIcon,
        EyeIcon,
        PencilAltIcon
    } from '@heroicons/vue/solid'

    export default defineComponent({
        components: {
            AppLayout,
            CheckCircleIcon,
            ChevronLeftIcon,
            ChevronRightIcon,
            MailIcon,
            EyeIcon,
            Link,
            Pagination,
            PencilAltIcon,
            Popper,
            XCircleIcon
        },
        props: ['users','filters'],
        data() {
            return {
                search: this.filters.search
            }
        },
        methods: {
        },
        watch: {
            search: debounce(function (value) {
                this.$inertia.get(route('users'), { search: value },{
                    preserveState: true,
                    replace: true
                })
            },500)
        }
    })
</script>

<style scoped>
  :deep(.popper) {
    background: #32a85e;
    padding: 20px;
    border-radius: 20px;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
  }

  :deep(.popper #arrow::before) {
    background: #32a85e;
  }

  :deep(.popper:hover),
  :deep(.popper:hover > #arrow::before) {
    background: #32a85e;
  }
</style>
