<template>
    <app-layout title="Application Details">
        <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <div class="p-4 bg-cyan-500 text-white font-extrabold text-3xl">
                Application Details
            </div>
            <div class="grid grid-cols-2 gap-4 p-4">
                <div class="col-span-2 md:col-span-1">
                    <div>
                        <label class="font-medium text-2xl">Control Number: </label>
                        <span class="font-bold text-2xl">{{application.control_number}}</span>
                    </div>
                    <div class="mt-1 inline-flex">
                        <label class="font-medium text-2xl">Status: </label>
                        <div v-html="application.status"></div>
                    </div>
                    <div class="mt-1">
                        <label class="font-medium text-2xl">Date Submitted: </label>
                        <span class="font-bold text-2xl">{{application.date_submitted}}</span>
                    </div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <div>
                        <template v-if="!isFinished">
                            <label class="block font-medium text-2xl mb-5">Time Left: </label>
                            <vue-countdown @end="onEndCountDown" :time="application.application_expiration" v-slot="{ days, hours, minutes, seconds }">
                                <span class="text-3xl font-bold">{{ days }} days, {{ hours }} hours, {{ minutes }} minutes</span>
                            </vue-countdown>
                        </template>
                        <template v-else>
                            <label class="block font-medium text-2xl mb-5">Processed Time: </label>
                            <vue-countdown :auto-start="false" :time="application.processed_time" v-slot="{ days, hours, minutes, seconds }">
                                <span class="text-3xl font-bold">{{ days }} days, {{ hours }} hours, {{ minutes }} minutes</span>
                            </vue-countdown>
                        </template>
                    </div>
                </div>
            </div>
            <div v-if="(user.can_provide_resolution || application.resolution.file) && application.status_data == 'approved'" class="p-4">
                <Resolution :draft="application.resolution_draft" :id="application.id" :resolution="application.resolution" />
            </div>
            <div class="grid grid-cols-3 gap-4 p-4">
                <div class="col-span-3 md:col-span-1 border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        Information
                    </div>
                    <div class="p-4">
                        <div>
                            <label class="font-bold">When: </label>
                            <span>{{application.when}}</span>
                        </div>
                        <div class="mt-2">
                            <label class="font-bold">Where: </label>
                            <span>{{application.where}}</span>
                        </div>
                        <div class="mt-2">
                            <label class="font-bold">What: </label>
                            <span>{{application.what}}</span>
                        </div>
                        <div class="mt-2">
                            <label class="font-bold">How: </label>
                            <span>{{application.how}}</span>
                        </div>
                        <div class="mt-2">
                            <label class="font-bold">Other Details: </label>
                            <span>{{application.other_details}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 md:col-span-1 border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <img class="w-full" :src="application.arrested_picture" :alt="application.arrested_fullname">
                    <div class="p-4">
                        <span class="font-bold text-2xl">{{application.arrested_fullname}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-2 gap-4 col-span-3 md:col-span-1">
                    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <div class="p-4 bg-cyan-500 text-white font-extrabold">
                            Attachments
                        </div>
                        <div class="p-4">
                            <div>
                                <a :href="application.sworn_affidavit" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Sworn Affidavit of Arresting Officer
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                            <div v-if="application.sworn_affidavit_witness" class="mt-3">
                                <a :href="application.sworn_affidavit_witness" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Sworn Affidavit of Witness
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                            <div v-if="application.warrantless_arrest" class="mt-3">
                                <a :href="application.warrantless_arrest" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Copy of Notice of Warrantless Arrest
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a :href="application.book_sheet" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Booking Sheet
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a :href="application.spot_report" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Spot Report
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a :href="application.logbook" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">Log Book
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <div class="p-4 bg-cyan-500 text-white font-extrabold">
                            Supporting Documents
                        </div>
                        <div class="p-4">
                            <div v-for="(document,index) in application.supporting_documents">
                                <a :href="document.original_url" target="_blank" class="inline-flex text-blue-600 no-underline hover:underline cursor-pointer">{{document.name + '.' + document.extension}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                  </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        Arrested Person Info
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div>
                            <div>
                                <label class="block font-bold">Address: </label>
                                <span>{{application.arrested_address}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Tel No: </label>
                                <span>{{application.arrested_tel}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Place of Birth: </label>
                                <span>{{application.arrested_pob}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Date of Birth: </label>
                                <span>{{application.arrested_dob}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Gender: </label>
                                <span>{{application.arrested_sex}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Marital Status: </label>
                                <span>{{application.arrested_status}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Spouse/s and their Address: </label>
                                <span v-for="(spouse,index) in application.arrested_spouse" class="block">{{spouse}} {{application.arrested_spouse_address[index]}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Age: </label>
                                <span>{{application.arrested_age}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Weight(kg): </label>
                                <span>{{application.arrested_weight}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Height: </label>
                                <span>{{application.arrested_height}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Occupation: </label>
                                <span>{{application.arrested_occupation}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Nationality: </label>
                                <span>{{application.arrested_nationality}}</span>
                            </div>

                        </div>
                        <div>
                            <div>
                                <label class="block font-bold">Tribe: </label>
                                <span>{{application.arrested_tribe}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Dialect/Language: </label>
                                <span class="block">{{application.arrested_language}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Highest School Attainment: </label>
                                <span>{{application.arrested_educ_attainment}}</span>
                            </div>
                            <div  class="mt-2">
                                <label class="block font-bold">Name of School: </label>
                                <span>{{application.arrested_school_name}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">School Address: </label>
                                <span>{{application.arrested_school_address}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Eyes: </label>
                                <span>{{application.arrested_eyes}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Hair: </label>
                                <span>{{application.arrested_hair}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Complexion: </label>
                                <span>{{application.arrested_complexion}}</span>
                            </div>

                            <div class="mt-2">
                                <label class="block font-bold">Physical Deformity: </label>
                                <span>{{application.arrested_defect}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Identifying Marks: </label>
                                <span>{{application.arrested_marks}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Location of Identifying Marks: </label>
                                <span>{{application.arrested_location_marks}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        Arresting Officer Info
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div>
                            <div>
                                <label class="block font-bold">Name: </label>
                                <span>{{application.name}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Rank: </label>
                                <span class="block">{{application.rank}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Badge Number: </label>
                                <span class="block">{{application.badge_number}}</span>
                            </div>
                        </div>
                        <div>
                            <div>
                                <label class="block font-bold">Unit: </label>
                                <span>{{application.unit}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Address: </label>
                                <span class="block">{{application.office_address}}</span>
                            </div>
                            <div class="mt-2">
                                <label class="block font-bold">Contact No: </label>
                                <span class="block">{{application.tel}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <span class="text-2xl font-extrabold">Reason for Application for Authorization for Extension of Detention</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-4 pb-4">
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        Reasons
                    </div>
                    <div class="p-4">
                        <fieldset>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="preserve" name="extension_reason" type="checkbox" value="A" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.extension_reason" disabled="disabled" />
                                    <label for="preserve" class="ml-3 block font-medium text-gray-700">
                                        To preserve evidence related to terrorism
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="complete_investigation" name="extension_reason" type="checkbox" value="B" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.extension_reason" disabled="disabled" />
                                    <label for="complete_investigation" class="ml-3 block font-medium text-gray-700">
                                        To complete the investigation
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="prevent_terrorism" name="extension_reason" type="checkbox" value="C" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.extension_reason" disabled="disabled" />
                                    <label for="prevent_terrorism" class="ml-3 block font-medium text-gray-700">
                                        To prevent the commision of another terrorism
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold" :class="{'flex justify-between': application.can_edit_narrative}">
                        <span>Brief Narrative</span>
                        <div v-if="application.can_edit_narrative">
                            <Popper arrow content="Edit Narrative" :hover="true">
                                <button @click="editNarrative = true"><PencilAltIcon class="h-5 w-5"/></button>
                            </Popper>
                        </div>
                    </div>
                    <div class="p-4">
                        <span v-if="!editNarrative">{{application.reason_narration}}</span>
                        <div v-else>
                            <textarea class="form-input" v-model="form.reason_narration"></textarea>
                            <jet-input-error class="mt-2" :message="form.errors.reason_narration" />
                            <div class="text-right mt-4">
                                <button class="btn btn-primary" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="updateNarrative">Update</button>
                                 <button class="btn btn-danger ml-4" @click="editNarrative = false & form.reset()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <span class="text-2xl font-extrabold">Warrantless Arrest</span>
            </div>
            <div class="px-4 pb-4">
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        PHYSICAL CONDITION OF THE DETAINED SUSPECT
                    </div>
                    <div class="p-4">
                        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Question</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Yes</th>
                                        <th scope="col" class="px-3 pr-6 py-3.5 text-left font-semibold text-white">No</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Is the detained suspect injured?
                                            <div v-if="application.physical_condition.answers.question1 == 'yes'">
                                                Please describe injury/ies:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.physical_condition.more_details.question1" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question1" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question1" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Is the detained suspect conscious?
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question2" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question2" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect appear to be physically disabled?
                                            <div v-if="application.physical_condition.answers.question3 == 'yes'">
                                                Please specify physical disability:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.physical_condition.more_details.question3" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question3" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question3" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit any immediately observable symptom of being sick (like chills, fever, colds etc.)?
                                            <div v-if="application.physical_condition.answers.question4 == 'yes'">
                                                Please specify observed symptom:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.physical_condition.more_details.question4" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question4" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question4" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Are there other noticeable features on the physical condition of the detained suspect?
                                            <div v-if="application.physical_condition.answers.question5 == 'yes'">
                                                Please specify:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.physical_condition.more_details.question5" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question5" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.physical_condition.answers.question5" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <div class="p-4 bg-cyan-500 text-white font-extrabold">
                        MENTAL CONDITION OF THE DETAINED SUSPECT
                    </div>
                    <div class="p-4">
                        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Question</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-white">Yes</th>
                                        <th scope="col" class="px-3 pr-6 py-3.5 text-left font-semibold text-white">No</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect communicate coherently?
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question1" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question1" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Is the detained person calm and cooperative?
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question2" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question2" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit signs of violent behavior under custody?
                                            <div v-if="application.mental_condition.answers.question3 == 'yes'">
                                                Please elaborate:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.mental_condition.more_details.question3" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question3" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question3" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit visible signs of trauma or anxiety?
                                            <div v-if="application.mental_condition.answers.question4 == 'yes'">
                                                Please elaborate:
                                                <input type="text" class="block w-full pr-10 focus:outline-none rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 bg-gray-300" v-model="application.mental_condition.more_details.question4" disabled="disabled">
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question4" value="yes" disabled="disabled"/>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="application.mental_condition.answers.question4" value="no" disabled="disabled"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 pb-4">
                <div class="p-4 border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <label class="form-label"> DOES THE DETAINED SUSPECT BELONG TO ANY OF THE FOLLOWING CATEGORIES? </label>
                    <fieldset class="mt-4">
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input id="elderly" type="radio" value="elderly" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.arrested_category" disabled="disabled"  />
                                <label for="elderly" class="ml-2 block font-medium text-gray-700">
                                    Elderly
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="pwd" type="radio" value="pwd" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.arrested_category" disabled="disabled"/>
                                <label for="pwd" class="ml-2 block font-medium text-gray-700">
                                    PWD
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="pregnant_woman" type="radio" value="pregnant_woman" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.arrested_category" disabled="disabled"/>
                                <label for="pregnant_woman" class="ml-2 block font-medium text-gray-700">
                                    Pregnant Woman
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="woman" type="radio" value="woman" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.arrested_category" disabled="disabled"/>
                                <label for="woman" class="ml-2 block font-medium text-gray-700">
                                    Woman
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="children" type="radio" value="children" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.arrested_category" disabled="disabled"/>
                                <label for="children" class="ml-2 block font-medium text-gray-700">
                                    Children
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="px-4 pb-4">
                <div class="p-4 border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <label class="form-label"> WAS THE DETAINED SUSPECT INFORMED OF HER/HIS RIGHTS SPECIFIED? </label>
                    <fieldset class="mt-4">
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input id="is_informed_of_right-yes" type="radio" value="yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.is_informed_of_right" disabled="disabled" />
                                <label for="is_informed_of_right-yes" class="ml-2 block font-medium text-gray-700">
                                    YES
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="is_informed_of_right-no" type="radio" value="no" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="application.is_informed_of_right" disabled="disabled" />
                                <label for="is_informed_of_right-no" class="ml-2 block font-medium text-gray-700">
                                    NO
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <!-- Approval Remarks -->
            <Remarks
            v-if="user.can_view_other_application && application.approve.date"
            title="Secretariat Remarks"
            :date="application.approve.date"
            :remarks="application.approve.remarks"
            :documents="application.approve.attachments"
            />
            <!-- Discussions -->
            <div v-if="(application.can_comment || user.can_view_discussion) && application.status_data !== 'available'" class="px-4 pb-4">
                <Discussion
                :canComment="application.can_comment"
                :comments="comments"
                :id="application.id"
                :status="application.status_data"
                :numberEndorse="current_endorse"
                />
            </div>
            <!-- Vote -->
            <div v-if="(application.can_vote || user.can_view_vote) && application.status_data !== 'available' && application.status_data !== 'endorsing'" class="px-4 pb-4">
                <Vote
                :canVote="application.can_vote"
                :votes="votes"
                :id="application.id"
                :status="application.status_data"
                :maxVote="max_votes"
                />
            </div>

            <!-- Footer -->
            <div class="px-4 py-4 bg-gray-50 text-right sm:px-6">
                <button v-if="application.can_approve" type="submit" class="btn btn-primary" @click="openApproveModal">Accept Application</button>
                <button v-if="application.can_endorse" type="submit" class="btn btn-primary" @click="openEndorseModal">Endorse Application</button>
            </div>
        </div>
        <ApproveRemarksModal
        :show="approveModal"
        @close="closeApproveModal"
        :id="application.id"
        />
        <EndorseRemarksModal
        :show="endorseModal"
        @close="closeEndorseModal"
        :id="application.id"
        />
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { router, usePage } from '@inertiajs/vue3'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import VueCountdown from '@chenfengyuan/vue-countdown'
    import JetInputError from '@/Jetstream/InputError.vue'
    import Discussion from '@/Pages/Application/Partial/Discussion.vue'
    import Remarks from '@/Pages/Application/Partial/Remarks.vue'
    import ApproveRemarksModal from '@/Pages/Application/Partial/ApproveRemarksModal.vue'
    import EndorseRemarksModal from '@/Pages/Application/Partial/EndorseRemarksModal.vue'
    import Resolution from '@/Pages/Application/Partial/Resolution.vue'
    import Vote from '@/Pages/Application/Partial/Vote.vue'

    import Modal from '@/Components/Modal.vue'

    import { PencilAltIcon } from '@heroicons/vue/solid'

    import Popper from 'vue3-popper'

    export default defineComponent({
        components: {
            AppLayout,
            ApproveRemarksModal,
            Discussion,
            EndorseRemarksModal,
            Modal,
            JetInputError,
            PencilAltIcon,
            Popper,
            Remarks,
            Resolution,
            Vote,
            VueCountdown
        },
        data() {
            return {
                approveModal: false,
                endorseModal: false,
                editNarrative: false,
                form: this.$inertia.form({
                    reason_narration: this.applicationData.data.reason_narration
                }),
            }
        },
        props:['applicationData', 'max_votes', 'comments', 'votes', 'current_endorse'],
        created() {
            window.Echo.private(`update.${this.application.id}`)
                .listen('\\App\\Events\\Application\\CommentPosted', (e) => {
                    // reload only when you are not the sender
                    if (e.comment.user_id !== this.user.id) {
                        router.reload({ only: ['comments'] })
                    }
                })
                .listen('\\App\\Events\\Application\\ApplicationVoted', (e) => {
                    // reload only when you are not the voter
                    if (e.vote.user_id !== this.user.id) {
                        router.reload({ only: ['votes'] })
                    }
                })
        },
        methods: {
            onEndCountDown() {
                if (this.application.application_expiration === 0) {
                    return
                }

                window.location.reload(true);
            },
            openApproveModal() {
                this.approveModal = true
            },
            closeApproveModal() {
                this.approveModal = false
            },
            openEndorseModal() {
                this.form.post(route('applications.endorse',{application: this.applicationData.data.id}),{
                    preserveScroll: true,
                    onSuccess: () => {},
                })
            },
            closeEndorseModal() {
                this.endorseModal = false
            },
            updateNarrative() {
                this.form.patch(route('applications.narrative',{application: this.applicationData.data.id}),{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.editNarrative = false
                    }
                })
            }
        },
        computed: {
            application() {
                return this.applicationData.data
            },
            isFinished() {
                return this.application.resolution.file
                    || this.application.status_data === 'disapproved'
                    || this.application.status_data === 'expired'
            },
            user() {
                return usePage().props.user
            }
        }
    })
</script>

<style scoped>
.filepond--item {
    width: calc(50% - 0.5em);
}
@media (min-width: 30em) {
    .filepond--item {
        width: calc(50% - 0.5em);
    }
}

@media (min-width: 50em) {
    .filepond--item {
        width: calc(33.33% - 0.5em);
    }
}

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
