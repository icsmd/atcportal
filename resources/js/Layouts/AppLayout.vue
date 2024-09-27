<template>
    <div :class="fontSizeClass">
        <Head :title="title" />

        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="fixed inset-0 flex z-40 md:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <XIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                            <div class="flex-shrink-0 flex items-center px-4 justify-center">
                                <img class="h-50 w-auto" src="/images/logo.png" alt="ATC Portal" />
                            </div>
                            <nav class="mt-5 px-2 space-y-1">
                                <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                                    <component :is="homeIcon" :class="[route().current('dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                                    Dashboard
                                </Link>

                                <Link v-if="$page.props.user.can_send_application" :href="route('applications.create')" :class="[route().current('applications.create') || route().current('applications.edit') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                                    <component :is="clipboardCheckIcon" :class="[route().current('applications.create') || route().current('applications.edit') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                                    Application for Extension of Detention
                                </Link>

                                <Link :href="route('applications')" :class="[route().current('applications') || route().current('applications.show') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                                    <component :is="collectionIcon" :class="[route().current('applications') || route().current('applications.show') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                                    Application List
                                </Link>

                                <Link :href="route('applications.archive')" :class="[route().current('applications.archive*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                                    <component :is="archiveIcon" :class="[route().current('applications.archive*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                                    Archive
                                </Link>

                                <Link v-if="$page.props.user.can_manage_user" :href="route('users')" :class="[route().current('users*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                                    <component :is="usersIcon" :class="[route().current('users*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                                    User Management
                                </Link>

                                <form method="POST" @submit.prevent="logout">
                                    <button class="w-full text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2  font-medium rounded-md">
                                        <component :is="logoutIcon" class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true" />
                                        Logout
                                    </button>
                                </form>
                            </nav>
                        </div>
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <a href="#" class="flex-shrink-0 group block">
                                <div class="flex items-center">
                                    <div>
                                        <img class="inline-block h-10 w-10 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                    </div>
                                    <div class="ml-3">
                                        <p class=" font-medium text-gray-700 group-hover:text-gray-900">{{ $page.props.user.name }}</p>
                                        <Link :href="route('profile.show')">
                                            <p class="font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                                        </Link>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </TransitionChild>
                <div class="flex-shrink-0 w-14">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4 justify-center">
                        <img class="h-50 w-auto" src="/images/logo.png" alt="ATC Portal" />
                    </div>
                    <nav class="mt-5 flex-1 px-2 bg-white space-y-1">
                        <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                            <component :is="homeIcon" :class="[route().current('dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            Dashboard
                        </Link>

                        <Link v-if="$page.props.user.can_send_application" :href="route('applications.create')" :class="[route().current('applications.create') || route().current('applications.edit') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                            <component :is="clipboardCheckIcon" :class="[route().current('applications.create') || route().current('applications.edit') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            Application for Extension of Detention
                        </Link>

                        <Link :href="route('applications')" :class="[route().current('applications') || route().current('applications.show') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                            <component :is="collectionIcon" :class="[route().current('applications') || route().current('applications.show') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            Application List
                        </Link>

                        <Link :href="route('applications.archive')" :class="[route().current('applications.archive*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                            <component :is="archiveIcon" :class="[route().current('applications.archive*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            Archive
                        </Link>

                        <Link v-if="$page.props.user.can_manage_user" :href="route('users')" :class="[route().current('users*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2  font-medium rounded-md']">
                            <component :is="usersIcon" :class="[route().current('users*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0 h-6 w-6']" aria-hidden="true" />
                            User Management
                        </Link>

                        <form method="POST" @submit.prevent="logout">
                            <button class="w-full text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2  font-medium rounded-md">
                                <component :is="logoutIcon" class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" aria-hidden="true" />
                                Logout
                            </button>
                        </form>
                    </nav>
                    <div class="flex flex-col space-y-2 p-4 w-full text-gray-600 mb-2">
                        <input type="range" class="w-full" min="1" max="4" step="1" v-model="fontSize" @change="updateFont" />
                        <ul class="flex justify-between w-full px-[10px]">
                            <li class="flex justify-center relative"><span class="absolute">small</span></li>
                            <li class="flex justify-center relative"><span class="absolute">medium</span></li>
                            <li class="flex justify-center relative"><span class="absolute">large</span></li>
                            <li class="flex justify-center relative"><span class="absolute">xl</span></li>
                        </ul>
                    </div>

                </div>
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <a href="#" class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </div>
                            <div class="ml-3">
                                <p class=" font-medium text-gray-700 group-hover:text-gray-900">{{ $page.props.user.name }}</p>
                                <Link :href="route('profile.show')">
                                    <p class="font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                                </Link>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="md:pl-64 flex flex-col flex-1">
            <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-white">
                <button type="button" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <MenuIcon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl md:px-4 px-2">
                        <!-- Replace with your content -->
                        <div class="py-6">
                            <Banner />
                            <slot></slot>
                        </div>
                        <!-- /End replace -->
                    </div>
                </div>
            </main>
        </div>

    </div>
</template>

<script>
    import { ref, computed } from 'vue'
    import { Dialog, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue'
    import {
        ClipboardCheckIcon,
        CollectionIcon,
        HomeIcon,
        ArchiveIcon,
        MenuIcon,
        UsersIcon,
        XIcon,
        LogoutIcon,
    } from '@heroicons/vue/outline'
    import Banner from '@/Components/Banner.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Link, Head, usePage } from '@inertiajs/vue3'

    export default {
        components: {
            Head,
            Dialog,
            DialogOverlay,
            Banner,
            TransitionChild,
            TransitionRoot,
            MenuIcon,
            XIcon,
            Link,
            JetNavLink,
            JetResponsiveNavLink
        },

        props: {
            title: String,
        },

        setup() {
            const fontSizes = ref(['small','medium','large','huge'])
            const fontSize = ref(fontSizes.value.indexOf(usePage().props.user.font_size) + 1)
            const sidebarOpen = ref(false)
            const homeIcon = HomeIcon
            const clipboardCheckIcon = ClipboardCheckIcon
            const collectionIcon = CollectionIcon
            const archiveIcon = ArchiveIcon
            const usersIcon = UsersIcon
            const logoutIcon = LogoutIcon

            const fontSizeClass = computed(() => {
                if (fontSize.value === 1)
                    return 'text-sm'

                if (fontSize.value === 2)
                    return 'text-base'

                if (fontSize.value === 3)
                    return 'text-lg'

                if (fontSize.value === 4)
                    return 'text-xl'
            })

            return {
                fontSizes,
                fontSizeClass,
                fontSize,
                homeIcon,
                clipboardCheckIcon,
                collectionIcon,
                archiveIcon,
                usersIcon,
                logoutIcon,
                sidebarOpen,
            }
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
            async updateFont() {
                await axios.post(route('font.change'),{
                    'font_size': this.fontSizes[this.fontSize-1]
                })
            }
        }
    }
</script>
