<template>
    <app-layout title="Archived Application List">
        <Table>
            <template #headers>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Application Number</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Arresting Officer</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Arrested Person</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Date/Time of Arrest</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Status</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Processed Time</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Action</th>
                </tr>
            </template>
            <template #data>
                <tr v-for="application in applications.data">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                        {{application.control_number}}
                        <span v-if="application.is_extension" class="badge badge-danger">Extend</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">{{application.officer}}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">{{application.person}}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">{{application.when}}</td>
                    <td class="whitespace-nowrap px-3 py-4"><div v-html="application.status_label"></div></td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">
                        <vue-countdown :auto-start="false"
                                       :time="application.processed_time"
                                       v-slot="{ days, hours, minutes, seconds }"
                        >
                            <div class="flex space-x-12">
                                <div class="justify-left font-bold">
                                    {{ days }}
                                </div>
                                <div class="justify-left font-bold">
                                    {{ hours }}
                                </div>
                                <div class="justify-left font-bold">
                                    {{ minutes }}
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <div class="justify-left font-bold">
                                    days
                                </div>
                                <div class="justify-left font-bold">
                                    hours
                                </div>
                                <div class="justify-left font-bold">
                                    minutes
                                </div>
                            </div>
                        </vue-countdown>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">
                        <Popper arrow content="View Application" :hover="true">
                            <Link :href="route('applications.archive.show',{application:application.id})" class="btn btn-primary p-2">
                                <EyeIcon class="h-5 w-5" aria-hidden="true" />
                            </Link>
                        </Popper>
                        <Popper v-if="$page.props.user.can_send_application" arrow content="Add Suspect With Same Entries" :hover="true">
                            <Link :href="route('applications.duplicate',{application:application.id})" class="btn btn-danger p-2 ml-2">
                                <DuplicateIcon class="h-5 w-5" aria-hidden="true" />
                            </Link>
                        </Popper>
                    </td>
                </tr>
            </template>
        </Table>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { Link } from '@inertiajs/vue3'
    import Table from '@/Components/Table.vue'
    import Popper from 'vue3-popper'
    import VueCountdown from '@chenfengyuan/vue-countdown'

    import { ClockIcon, DuplicateIcon, EyeIcon, PencilAltIcon } from '@heroicons/vue/solid'

    export default defineComponent({
        components: {
            AppLayout,
            ClockIcon,
            DuplicateIcon,
            EyeIcon,
            Link,
            PencilAltIcon,
            Popper,
            Table,
            VueCountdown
        },
        props: ['applications']
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
