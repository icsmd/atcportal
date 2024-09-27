<template>
    <app-layout title="Create Application">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs" v-model="currentTab" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md ">
                        <option value="applicant">Applicant</option>
                        <option value="arrested_person">Arrested Person</option>
                        <option value="details_arrest">Details of Arrest</option>
                        <option value="warrantless_arrest">Warrantless Arrest</option>
                        <option value="request_details">Request Details</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="relative z-0 rounded-lg shadow flex divide-x divide-gray-200" aria-label="Tabs">
                        <button @click="changeTab('applicant')" :class="[currentTab == 'applicant' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', 'rounded-l-lg', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4  font-medium text-center hover:bg-gray-50 focus:z-10', applicantHasError()]" :aria-current="currentTab == 'applicant' ? 'page' : undefined">
                            <span class="">Applicant</span>
                            <span aria-hidden="true" :class="[currentTab == 'applicant' ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                        </button>
                        <button @click="changeTab('arrested_person')" :class="[currentTab == 'arrested_person' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4  font-medium text-center hover:bg-gray-50 focus:z-10', arrestedPersonHasError()]" :aria-current="currentTab == 'arrested_person' ? 'page' : undefined">
                            <span class="">Arrested Person</span>
                            <span aria-hidden="true" :class="[currentTab == 'arrested_person' ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                        </button>
                        <button @click="changeTab('details_arrest')" :class="[currentTab == 'details_arrest' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4  font-medium text-center hover:bg-gray-50 focus:z-10',detailsArrestHasError()]" :aria-current="currentTab == 'details_arrest' ? 'page' : undefined">
                            <span class="">Details of Arrest</span>
                            <span aria-hidden="true" :class="[currentTab == 'details_arrest' ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                        </button>
                        <button @click="changeTab('warrantless_arrest')" :class="[currentTab == 'warrantless_arrest' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4  font-medium text-center hover:bg-gray-50 focus:z-10']" :aria-current="currentTab == 'warrantless_arrest' ? 'page' : undefined">
                            <span class="">Warrantless Arrest</span>
                            <span aria-hidden="true" :class="[currentTab == 'warrantless_arrest' ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                        </button>
                        <button @click="changeTab('request_details')" :class="[currentTab == 'request_details' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700','rounded-r-lg', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4  font-medium text-center hover:bg-gray-50 focus:z-10', requestDetailsHasError()]" :aria-current="currentTab == 'request_details' ? 'page' : undefined">
                            <span class="">Request Details</span>
                            <span aria-hidden="true" :class="[currentTab == 'request_details' ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                        </button>
                    </nav>
                </div>
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <!-- applicant -->
                    <div v-show="currentTab == 'applicant'" class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-9 gap-6">
                            <div class="col-span-9 sm:col-span-9 lg:col-span-2">
                                <label for="date" class="form-label"> Date </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <CalendarIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                    </div>
                                    <input type="text" id="date" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm: border-gray-300 rounded-md bg-gray-300" disabled="disabled" :value="currentDate" />
                                </div>
                            </div>

                            <div class="col-span-9">
                                <label for="name" class="form-label"> Name<span style="color: red">*</span> </label>
                                <input-validation type="text" id="name" v-model="form.name" :errorMessage="form.errors.name" placeholderValue="Enter name" />
                            </div>

                            <div class="col-span-9 md:col-span-3">
                                <label for="rank" class="form-label"> Rank<span style="color: red">*</span> </label>
                                <input-validation type="text" id="rank" v-model="form.rank" :errorMessage="form.errors.rank" placeholderValue="Enter rank" />
                            </div>

                            <div class="col-span-9 md:col-span-3">
                                <label for="badge_number" class="form-label"> Badge Number<span style="color: red">*</span> </label>
                                <input-validation type="text" id="badge_number" v-model="form.badge_number" :errorMessage="form.errors.badge_number" placeholderValue="Enter badge number" />
                            </div>

                            <div class="col-span-9 md:col-span-3">
                                <label for="unit" class="form-label"> Unit<span style="color: red">*</span> </label>
                                <input-validation type="text" id="unit" v-model="form.unit" :errorMessage="form.errors.unit" placeholderValue="Enter unit" />
                            </div>

                            <div class="col-span-9">
                                <label for="office_address" class="form-label"> Office Address<span style="color: red">*</span> </label>
                                <input-validation type="text" id="office_address" v-model="form.office_address" :errorMessage="form.errors.office_address" placeholderValue="Enter office address" />
                            </div>

                            <div class="col-span-9 md:col-span-3">
                                <label for="tel" class="form-label"> Contact No.<span style="color: red">*</span> </label>
                                <input-validation type="text" id="tel" v-model="form.tel" :errorMessage="form.errors.tel" placeholderValue="+639xxxxxxxxx" />
                            </div>
                        </div>
                    </div>

                    <!-- arrested_person -->
                    <div v-show="currentTab == 'arrested_person'" class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12">
                                <label for="arrested_picture" class="form-label"> Mugshot of the Suspect<span style="color: red">*</span> </label>
                                <input type="file" id="arrested_picture" @input="form.arrested_picture = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" accept="image/*" />
                                <jet-input-error :message="form.errors.arrested_picture" class="mt-2" />
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_lastname" class="form-label"> Last Name<span style="color: red">*</span> </label>
                                <input-validation type="text" id="arrested_lastname" v-model="form.arrested_lastname" :errorMessage="form.errors.arrested_lastname" placeholderValue="Enter last name" />
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_firstname" class="form-label"> First Name<span style="color: red">*</span> </label>
                                <input-validation type="text" id="arrested_firstname" v-model="form.arrested_firstname" :errorMessage="form.errors.arrested_firstname" placeholderValue="Enter first name" />
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_middlename" class="form-label"> Middle Name </label>
                                <input-validation type="text" id="arrested_middlename" v-model="form.arrested_middlename" :errorMessage="form.errors.arrested_middlename" placeholderValue="Enter middle name" />
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_suffix" class="form-label"> Suffix </label>
                                <input-validation type="text" id="arrested_suffix" v-model="form.arrested_suffix" :errorMessage="form.errors.arrested_suffix" placeholderValue="Enter suffix" />
                            </div>

                            <div class="col-span-12">
                                <label for="arrested_address" class="form-label"> Full Address<span style="color: red">*</span> </label>
                                <input-validation type="text" id="arrested_address" v-model="form.arrested_address" :errorMessage="form.errors.arrested_address" placeholderValue="Enter full address" />
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label for="arrested_tel" class="form-label"> Tel No.<span style="color: red">*</span> </label>
                                <input-validation type="text" id="arrested_tel" v-model="form.arrested_tel" :errorMessage="form.errors.arrested_tel" placeholderValue="Enter telephone number" />
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label for="arrested_pob" class="form-label"> Place of Birth<span style="color: red">*</span> </label>
                                <input-validation type="text" id="arrested_pob" v-model="form.arrested_pob" :errorMessage="form.errors.arrested_pob" placeholderValue="Enter place of birth" />
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label for="arrested_dob" class="form-label"> Date of Birth </label>
                                <Datepicker id="arrested_dob" :class="changeClassIfError(form.errors.arrested_dob)" v-model="form.arrested_dob" :hideInputIcon="true" :format="'dd MMMM yyyy'" :enableTimePicker="false" :yearRange="[1900, 2100]"/>
                                <jet-input-error :message="form.errors.arrested_dob" class="mt-2" />
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="form-label"> Marital Status<span style="color: red">*</span> </label>
                                <fieldset class="mt-4">
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input id="single" name="marital-status" type="radio" value="single" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_status" />
                                            <label for="single" class="ml-3 block  font-medium text-gray-700">
                                                Single
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="married" name="marital-status" type="radio" value="married" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_status" />
                                            <label for="married" class="ml-3 block  font-medium text-gray-700">
                                                Married
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="widowed" name="marital-status" type="radio" value="widowed" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_status" />
                                            <label for="widowed" class="ml-3 block  font-medium text-gray-700">
                                                Widowed
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="separated" name="marital-status" type="radio" value="separated" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_status" />
                                            <label for="separated" class="ml-3 block  font-medium text-gray-700">
                                                Separated
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <jet-input-error :message="form.errors.arrested_status" class="mt-2" />
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="form-label"> Sex<span style="color: red">*</span> </label>
                                <fieldset class="mt-4">
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input id="male" name="sex" type="radio" value="male" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_sex" />
                                            <label for="male" class="ml-3 block  font-medium text-gray-700">
                                                Male
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="female" name="sex" type="radio" value="female" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_sex" />
                                            <label for="female" class="ml-3 block  font-medium text-gray-700">
                                                Female
                                            </label>
                                        </div>
                                     </div>
                                </fieldset>
                                <jet-input-error :message="form.errors.arrested_sex" class="mt-2" />
                            </div>

                            <div class="col-span-12 md:col-span-4" />

                            <div class="grid grid-cols-12 gap-6 col-span-12" v-for="(name,k) in form.arrested_spouse_name" :key="k">
                                <div class="col-span-12 md:col-span-4">
                                    <label class="form-label">Name of Spouse</label>
                                    <input-validation type="text" id="arrested_spouse_name" v-model="form.arrested_spouse_name[k]" :errorMessage="form.errors.arrested_spouse_name" placeholderValue="Enter Name of Spouse" />
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Address</label>
                                    <input-validation type="text" id="arrested_spouse_address" v-model="form.arrested_spouse_address[k]" :errorMessage="form.errors.arrested_spouse_address" placeholderValue="Enter Spouse Address" />
                                </div>

                                <div class="col-span-12 md:col-span-2">
                                    <button class="md:mt-7 btn btn-danger" @click="remove(k)" v-show="k || ( !k && form.arrested_spouse_name.length > 1)">
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <div class="col-span-4">
                                <button class="btn btn-success" @click="add()">
                                    Add Spouse
                                </button>
                            </div>

                            <div class="col-span-8" />

                            <div class="col-span-12 md:col-span-6">
                                <label for="arrested_occupation" class="form-label"> Occupation<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_occupation" v-model="form.arrested_occupation" :errorMessage="form.errors.arrested_occupation" placeholderValue="Enter occupation" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="arrested_nationality" class="form-label"> Nationality<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_nationality" v-model="form.arrested_nationality" :errorMessage="form.errors.arrested_nationality" placeholderValue="Enter nationality" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="arrested_tribe" class="form-label"> Tribe/Group<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_tribe" v-model="form.arrested_tribe" :errorMessage="form.errors.arrested_tribe" placeholderValue="Enter ethnicity" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="arrested_language" class="form-label"> Dialect/Language<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_language" v-model="form.arrested_language" :errorMessage="form.errors.arrested_language" placeholderValue="Enter spoken language" />
                            </div>

                            <div class="col-span-12">
                                <label for="arrested_educ_attainment" class="form-label">Highest School Attainment<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_educ_attainment" v-model="form.arrested_educ_attainment" :errorMessage="form.errors.arrested_educ_attainment" placeholderValue="Enter educational attainment" />
                            </div>
                            <div class="col-span-12">
                                <label for="arrested_school_name" class="form-label">Name of school<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_school_name" v-model="form.arrested_school_name" :errorMessage="form.errors.arrested_school_name" placeholderValue="Enter school name" />
                            </div>
                            <div class="col-span-12">
                                <label for="arrested_school_address" class="form-label">Location of school<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_school_address" v-model="form.arrested_school_address" :errorMessage="form.errors.arrested_school_address" placeholderValue="Enter school location" />
                            </div>

                            <div class="col-span-12">
                                <label class="block  font-extrabold text-2xl text-gray-700">Physical Depiction</label>
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_age" class="form-label"> Age</label>
                                <input-validation type="text" id="arrested_age" v-model="form.arrested_age" :errorMessage="form.errors.arrested_age" placeholderValue="Enter age" />
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_eyes" class="form-label"> Eyes<span style="color: red">*</span></label>
                                <Select v-model="form.arrested_eyes" :lists="eyes" />
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_hair" class="form-label"> Hair<span style="color: red">*</span></label>
                                <Select v-model="form.arrested_hair" :lists="hairs" />
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_complexion" class="form-label"> Complexion<span style="color: red">*</span></label>
                                <Select v-model="form.arrested_complexion" :lists="complexions" />
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <label for="arrested_weight" class="form-label"> Weight<span style="color: red">*</span></label>
                                <input-validation type="text" id="arrested_weight" v-model="form.arrested_weight" :errorMessage="form.errors.arrested_weight" placeholderValue="Enter weight in kg" />
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label for="arrested_height" class="form-label"> Height<span style="color: red">*</span></label>
                                <div class="inline-flex">
                                    <input-validation type="text" id="arrested_height" v-model="form.arrested_height" :errorMessage="form.errors.arrested_height" placeholderValue="Enter height" />
                                    <Select v-model="form.height_unit" class="ml-2" :lists="height_units" />
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-5">
                                <label class="form-label"> Identifying Marks</label>
                                <fieldset class="mt-4">
                                    <div class="flex space-x-4">
                                        <div class="flex items-center">
                                            <input id="mole" type="checkbox" value="mole" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_marks" />
                                            <label for="mole" class="ml-2 block  font-medium text-gray-700">
                                                Mole
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="tattoo" type="checkbox" value="tattoo" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_marks" />
                                            <label for="tattoo" class="ml-2 block  font-medium text-gray-700">
                                                Tattoo
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="birthmark" type="checkbox" value="birthmark" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_marks" />
                                            <label for="birthmark" class="ml-2 block  font-medium text-gray-700">
                                                Birthmark
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="scar" type="checkbox" value="scar" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_marks" />
                                            <label for="scar" class="ml-2 block  font-medium text-gray-700">
                                                Scar
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-span-12">
                                <label for="arrested_location_marks" class="form-label"> Location of Identifying Marks</label>
                                <textarea id="arrested_location_marks" class="block w-full pr-10 focus:outline-none sm: rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" v-model="form.arrested_location_marks" placeholder="Enter location of identifying marks..."></textarea>
                            </div>

                            <div class="col-span-12">
                                <label for="arrested_defect" class="form-label"> Physical Deformity</label>
                                <textarea id="arrested_defect" class="block w-full pr-10 focus:outline-none sm: rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" v-model="form.arrested_defect" placeholder="Enter physical deformity..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- details_arrest -->
                    <div v-show="currentTab == 'details_arrest'" class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label for="who" class="form-label"> Who</label>
                                <input type="text" id="who" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm: border-gray-300 rounded-md bg-gray-300" disabled="disabled" :value="fullName" />
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label for="when" class="form-label"> When<span style="color: red">*</span></label>
                                <Datepicker id="when" :class="changeClassIfError(form.errors.when)" v-model="form.when" :hideInputIcon="true" :format="'dd MMMM yyyy HHmm'" :is24="true"/>
                                <jet-input-error :message="form.errors.when" class="mt-2" />
                            </div>
                            <div class="col-span-12">
                                <label for="where" class="form-label"> Where<span style="color: red">*</span></label>
                                <input-validation type="text" id="where" v-model="form.where" :errorMessage="form.errors.where" placeholderValue="Enter place of incident" />
                            </div>
                            <div class="col-span-12">
                                <label for="what" class="form-label"> What<span style="color: red">*</span></label>
                                <input-validation type="text" id="what" v-model="form.what" :errorMessage="form.errors.what" placeholderValue="Enter crime committed" />
                            </div>
                            <div class="col-span-12">
                                <label for="how" class="form-label"> How<span style="color: red">*</span></label>
                                <input-validation type="text" id="how" v-model="form.how" :errorMessage="form.errors.how" placeholderValue="Enter how" />
                            </div>
                            <div class="col-span-12">
                                <label for="other_details" class="form-label"> Other Details<span style="color: red">*</span></label>
                                <textarea id="other_details" :class="changeClassIfError(form.errors.other_details)" v-model="form.other_details"></textarea>
                                <jet-input-error :message="form.errors.other_details" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- warrantless_arrest -->
                    <div v-show="currentTab == 'warrantless_arrest'" class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-2xl font-extrabold text-gray-900">PHYSICAL CONDITION OF THE DETAINED SUSPECT</h1>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                                <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2  font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto" @click="clearChoices">Clear All</button>
                            </div>
                        </div>
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
                                            <div v-if="form.physical_condition.answers.question1 == 'yes'">
                                                Please describe injury/ies:
                                                <input-validation type="text" v-model="form.physical_condition.more_details.question1"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question1" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question1" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Is the detained suspect conscious?
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question2" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question2" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect appear to be physically disabled?
                                            <div v-if="form.physical_condition.answers.question3 == 'yes'">
                                                Please specify physical disability:
                                                <input-validation type="text" v-model="form.physical_condition.more_details.question3"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question3" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question3" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit any immediately observable symptom of being sick (like chills, fever, colds etc.)?
                                            <div v-if="form.physical_condition.answers.question4 == 'yes'">
                                                Please specify observed symptom:
                                                <input-validation type="text" v-model="form.physical_condition.more_details.question4"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question4" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question4" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Are there other noticeable features on the physical condition of the detained suspect?
                                            <div v-if="form.physical_condition.answers.question5 == 'yes'">
                                                Please specify:
                                                <input-validation type="text" v-model="form.physical_condition.more_details.question5"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question5" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.physical_condition.answers.question5" value="no" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-2xl font-extrabold text-gray-900">MENTAL CONDITION OF THE DETAINED SUSPECT</h1>
                            </div>
                        </div>
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
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question1" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question1" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Is the detained person calm and cooperative?
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question2" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question2" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit signs of violent behavior under custody?
                                            <div v-if="form.mental_condition.answers.question3 == 'yes'">
                                                Please elaborate:
                                                <input-validation type="text" v-model="form.mental_condition.more_details.question3"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question3" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question3" value="no" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            Does the detained suspect exhibit visible signs of trauma or anxiety?
                                            <div v-if="form.mental_condition.answers.question4 == 'yes'">
                                                Please elaborate:
                                                <input-validation type="text" v-model="form.mental_condition.more_details.question4"  />
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question4" value="yes" />
                                        </td>
                                        <td class="relative whitespace-nowrap px-3 pr-6 py-4 text-gray-500">
                                            <input type="radio" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6" v-model="form.mental_condition.answers.question4" value="no" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 w-full">
                            <label class="form-label"> DOES THE DETAINED SUSPECT BELONG TO ANY OF THE FOLLOWING CATEGORIES? </label>
                            <fieldset class="mt-4">
                                <div class="flex space-x-4">
                                    <div class="flex items-center">
                                        <input id="elderly" type="radio" value="elderly" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_category" />
                                        <label for="elderly" class="ml-2 block  font-medium text-gray-700">
                                            Elderly
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="pwd" type="radio" value="pwd" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_category" />
                                        <label for="pwd" class="ml-2 block  font-medium text-gray-700">
                                            PWD
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="pregnant_woman" type="radio" value="pregnant_woman" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_category" />
                                        <label for="pregnant_woman" class="ml-2 block  font-medium text-gray-700">
                                            Pregnant Woman
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="woman" type="radio" value="woman" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_category" />
                                        <label for="woman" class="ml-2 block  font-medium text-gray-700">
                                            Woman
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="children" type="radio" value="children" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.arrested_category" />
                                        <label for="children" class="ml-2 block  font-medium text-gray-700">
                                            Children
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="mt-5 w-full">
                            <label class="form-label"> WAS THE DETAINED SUSPECT INFORMED OF HER/HIS RIGHTS? </label>
                            <fieldset class="mt-4">
                                <div class="flex space-x-4">
                                    <div class="flex items-center">
                                        <input id="is_informed_of_right-yes" type="radio" value="yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.is_informed_of_right" />
                                        <label for="is_informed_of_right-yes" class="ml-2 block  font-medium text-gray-700">
                                            YES
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="is_informed_of_right-no" type="radio" value="no" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.is_informed_of_right" />
                                        <label for="is_informed_of_right-no" class="ml-2 block  font-medium text-gray-700">
                                            NO
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <!-- request_details -->
                    <div v-show="currentTab == 'request_details'" class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Reason for Application For Extension</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <fieldset class="mt-4">
                                                <div class="space-y-2">
                                                    <div class="flex items-center">
                                                        <input id="preserve" name="extension_reason" type="checkbox" value="A" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.extension_reason" />
                                                        <label for="preserve" class="ml-3 block  font-medium text-gray-700">
                                                            To preserve evidence related to terrorism
                                                        </label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input id="complete_investigation" name="extension_reason" type="checkbox" value="B" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.extension_reason" />
                                                        <label for="complete_investigation" class="ml-3 block  font-medium text-gray-700">
                                                            To complete the investigation
                                                        </label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input id="prevent_terrorism" name="extension_reason" type="checkbox" value="C" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" v-model="form.extension_reason" />
                                                        <label for="prevent_terrorism" class="ml-3 block  font-medium text-gray-700">
                                                            To prevent the commision of another terrorism
                                                        </label>
                                                    </div>
                                                </div>
                                                <jet-input-error :message="form.errors.extension_reason" class="mt-2" />
                                            </fieldset>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Narration Of Reason For Extension<span style="color: red">*</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <textarea id="reason_narration" class="block w-full pr-10 focus:outline-none sm: rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" v-model="form.reason_narration" placeholder="Enter narration of reason for extension..."></textarea>
                                            <jet-input-error :message="form.errors.reason_narration" class="mt-2" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Attachments</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="sworn_affidavit" class="form-label"> Sworn Affidavit of Arresting Officer<span style="color: red">*</span></label>
                                            <input type="file" id="sworn_affidavit" @input="form.sworn_affidavit = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.sworn_affidavit" class="mt-2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="sworn_affidavit_witness" class="form-label"> Sworn Affidavit of Witness</label>
                                            <input type="file" id="sworn_affidavit_witness" @input="form.sworn_affidavit_witness = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.sworn_affidavit_witness" class="mt-2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="warrantless_arrest" class="form-label"> Copy of Notice of Warrantless Arrest</label>
                                            <input type="file" id="warrantless_arrest" @input="form.warrantless_arrest = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.warrantless_arrest" class="mt-2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="book_sheet" class="form-label"> Booking Sheet<span style="color: red">*</span></label>
                                            <input type="file" id="book_sheet" @input="form.book_sheet = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.book_sheet" class="mt-2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="spot_report" class="form-label"> Spot Report<span style="color: red">*</span></label>
                                            <input type="file" id="spot_report" @input="form.spot_report = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.spot_report" class="mt-2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <label for="logbook" class="form-label"> Log Book<span style="color: red">*</span></label>
                                            <input type="file" id="logbook" @input="form.logbook = $event.target.files[0]" class="block w-full  text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file: file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                                            <jet-input-error :message="form.errors.logbook" class="mt-2" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 overflow-x-auto overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="w-full divide-y divide-gray-300">
                                <thead class="bg-green-600">
                                    <tr>
                                        <th scope="col" class="py-3.5 w-full pl-4 pr-3 text-left font-semibold text-white sm:pl-6">Supporting Documents</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                                            <div class="rounded-md bg-blue-50 p-4 mb-6">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <InformationCircleIcon class="h-5 w-5 text-blue-400" aria-hidden="true" />
                                                    </div>
                                                    <div class="ml-3 flex-1 md:flex md:justify-between">
                                                        <p class=" text-blue-700">Upload only 1GB per file.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <file-pond
                                            name="document"
                                            ref="pond"
                                            label-idle="Drop files here..."
                                            :allow-multiple="true"
                                            :server="server"
                                            :files="[...documents]"
                                            max-files="10"
                                            @processfile="onProcessfile"
                                            @removefile="onRemoveFile"
                                            @updatefiles="onUpdateFiles"
                                            @processfiles="onProcessFiles"
                                            @processfilestart="onProcessFileStart"
                                            maxFileSize="1000MB"
                                            />
                                            <jet-input-error :message="document_error" class="mt-2" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <template v-if="currentTab === 'request_details'">
                            <div class="flex justify-items-start mb-3">
                                <div class="h-5">
                                    <input id="terms" aria-describedby="terms-description" name="terms" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                           value="1" true-value="1" :false-value="null" v-model="form.terms"/>
                                </div>
                                <div>
                                    <label for="terms" class="font-medium text-gray-700 font-extrabold"><span style="color: red ">*</span>Disclaimer: By clicking “I AGREE”, you signify that you have understood and agree to our collection, storage, use, process and disclosure of your information, as applicable, in compliance with the Data Privacy Act of 2012.</label>
                                </div>
                            </div>
                            <jet-input-error :message="form.errors.terms" class="mt-2 relative flex items-start mb-3"/>
                        </template>
                        <button class="btn btn-primary" @click="sendApplication" :class="{ 'opacity-25': form.processing || uploadProgress }" :disabled="form.processing || uploadProgress">Submit</button>
                        <button class="btn btn-success ml-4" @click="saveDrafts" :class="{ 'opacity-25': form.processing || uploadProgress }" :disabled="form.processing || uploadProgress">Save as Draft</button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { CalendarIcon, InformationCircleIcon } from '@heroicons/vue/solid'
    import InputValidation from '@/Components/InputValidation.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import Select from '@/Components/Select.vue'

    import Datepicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'

    import moment from 'moment'

    import vueFilePond from "vue-filepond"
    import "filepond/dist/filepond.min.css"
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
    import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"

    const FilePond = vueFilePond(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    )

    export default defineComponent({
        components: {
            AppLayout,
            CalendarIcon,
            Datepicker,
            FilePond,
            InformationCircleIcon,
            InputValidation,
            JetInputError,
            Select
        },
        props: ['errors'],
        data() {
            return {
                eyes: ['brown','black','blue','hazel','green','defective','others'],
                hairs: ['brown','black','others'],
                complexions: ['fair','brown','black','others'],
                height_units: ['feet/meters','inches/cm'],
                currentTab: 'applicant',
                documents: [],
                temp_documents: [],
                server: {
                    process: route('upload.temporary'),
                    revert: route('upload.temporary.destroy'),
                    headers: {'X-CSRF-TOKEN': this.$page.props.csrf_token },
                },
                form: this.$inertia.form({
                    name: '',
                    rank: '',
                    badge_number: '',
                    unit: '',
                    office_address: '',
                    tel: '',
                    arrested_picture: null,
                    arrested_firstname: '',
                    arrested_middlename: '',
                    arrested_lastname: '',
                    arrested_suffix: '',
                    arrested_address: '',
                    arrested_tel: '',
                    arrested_pob: '',
                    arrested_dob: null,
                    arrested_sex: '',
                    arrested_status: '',
                    arrested_spouse_name: [''],
                    arrested_spouse_address: [''],
                    arrested_age: '',
                    arrested_weight: '',
                    arrested_height: '',
                    arrested_eyes: 'brown',
                    arrested_hair: 'brown',
                    arrested_complexion: 'fair',
                    arrested_occupation: '',
                    arrested_nationality: '',
                    arrested_tribe: '',
                    arrested_language: '',
                    arrested_educ_attainment: '',
                    arrested_school_name: '',
                    arrested_school_address: '',
                    arrested_marks: [''],
                    arrested_location_marks: '',
                    arrested_defect: '',
                    who: '',
                    when: '',
                    where: '',
                    what: '',
                    why: '',
                    how: '',
                    other_details:'',
                    terms: null,
                    physical_condition: {
                        answers: {
                            question1: '',
                            question2: '',
                            question3: '',
                            question4: '',
                            question5: '',
                        },
                        more_details: {
                            question1: '',
                            question2: '',
                            question3: '',
                            question4: '',
                            question5: '',
                        },
                    },
                    mental_condition: {
                        answers: {
                            question1: '',
                            question2: '',
                            question3: '',
                            question4: '',
                        },
                        more_details: {
                            question1: '',
                            question2: '',
                            question3: '',
                            question4: '',
                        },
                    },
                    arrested_category: '',
                    is_informed_of_right: '',
                    extension_reason: [''],
                    reason_narration: '',
                    sworn_affidavit: '',
                    spot_report: '',
                    logbook: '',
                    sworn_affidavit_witness: '',
                    warrantless_arrest: '',
                    book_sheet: '',
                    temporary_documents: [],
                    height_unit: 'feet/meters'
                }),
                document_error: '',
                uploadProgress: false
            }
        },
        methods: {
            changeTab(tab) {
                this.currentTab = tab
            },
            add() {
                this.form.arrested_spouse_name.push('');
                this.form.arrested_spouse_address.push('');
            },
            remove(index) {
                this.form.arrested_spouse_name.splice(index, 1);
                this.form.arrested_spouse_address.splice(index, 1);
            },
            changeClassIfError(errorMessage) {
                return {
                    'block': true,
                    'w-full': true,
                    'focus:outline-none': true,
                    'rounded-md': true,
                    'shadow-sm': true,
                    //Normal State
                    'focus:ring-indigo-500': errorMessage === undefined,
                    'focus:border-indigo-500': errorMessage === undefined,
                    'border-gray-300': errorMessage === undefined,
                    //Error State
                    'text-red-900': errorMessage,
                    'placeholder-red-300': errorMessage,
                    'border-red-300': errorMessage,
                    'focus:ring-red-500': errorMessage,
                    'focus:border-red-500': errorMessage,
                }
            },
            clearChoices() {
                this.form.reset('physical_condition', 'mental_condition','arrested_category','is_informed_of_right')
            },
            sendApplication() {
                this.form.clearErrors()
                this.form.transform((data) => ({
                    ...data,
                    when: moment(data.when).format('YYYY-MM-DD HH:mm'),
                    arrested_height: data.arrested_height === '' ? '' : data.arrested_height + ' ' + data.height_unit
                })).post(route('applications.store'))
            },
            saveDrafts() {
                this.form.clearErrors()
                this.form.transform((data) => ({
                    ...data,
                    when: data.when ? moment(data.when).format('YYYY-MM-DD HH:mm') : '',
                    arrested_height: data.arrested_height === '' ? '' : data.arrested_height + ' ' + data.height_unit
                })).post(route('applications.draft'))
            },
            onRemoveFile(error, file) {
                if (error) {
                    this.document_error = 'Ooopps! Something went wrong.'
                    return
                }

                let index = this.form.temporary_documents.indexOf(file.serverId)
                this.form.temporary_documents.splice(index,1)
            },
            onProcessfile(error, file){
                if (error) {
                    this.document_error = 'Please check the file and Upload it again.'
                    return
                }

                this.form.temporary_documents.push(file.serverId)
            },
            onUpdateFiles(files){
                this.documents = files
            },
            onProcessFileStart() {
                this.uploadProgress = true
            },
            onProcessFiles() {
                this.uploadProgress = false
            },
            applicantHasError() {
                if (this.form.errors.name || this.form.errors.rank || this.form.errors.badge_number || this.form.errors.unit || this.form.errors.office_address || this.form.errors.office_address) {
                    return 'bg-red-400 hover:bg-red-400 text-white'
                }
            },
            arrestedPersonHasError() {
                if (this.form.errors.arrested_picture ||
                    this.form.errors.arrested_lastname ||
                    this.form.errors.arrested_firstname ||
                    this.form.errors.arrested_middlename ||
                    this.form.errors.arrested_suffix ||
                    this.form.errors.arrested_address ||
                    this.form.errors.arrested_tel ||
                    this.form.errors.arrested_pob ||
                    this.form.errors.arrested_dob ||
                    this.form.errors.arrested_status ||
                    this.form.errors.arrested_sex ||
                    this.form.errors.arrested_spouse_name ||
                    this.form.errors.arrested_spouse_address ||
                    this.form.errors.arrested_occupation ||
                    this.form.errors.arrested_nationality ||
                    this.form.errors.arrested_tribe ||
                    this.form.errors.arrested_language ||
                    this.form.errors.arrested_educ_attainment ||
                    this.form.errors.arrested_school_name ||
                    this.form.errors.arrested_school_address ||
                    this.form.errors.arrested_age ||
                    this.form.errors.arrested_eyes ||
                    this.form.errors.arrested_hair ||
                    this.form.errors.arrested_complexion ||
                    this.form.errors.arrested_weight ||
                    this.form.errors.arrested_height ||
                    this.form.errors.arrested_marks ||
                    this.form.errors.arrested_location_marks ||
                    this.form.errors.arrested_defect ) {
                    return 'bg-red-400 hover:bg-red-400 text-white'
                }
            },
            detailsArrestHasError() {
                if (this.form.errors.when ||
                    this.form.errors.where ||
                    this.form.errors.what ||
                    this.form.errors.how ||
                    this.form.errors.other_details) {
                    return 'bg-red-400 hover:bg-red-400 text-white'
                }
            },
            requestDetailsHasError() {
                if (this.form.errors.extension_reason ||
                    this.form.errors.reason_narration ||
                    this.form.errors.sworn_affidavit ||
                    this.form.errors.sworn_affidavit_witness ||
                    this.form.errors.warrantless_arrest ||
                    this.form.errors.book_sheet ||
                    this.form.errors.spot_report ||
                    this.form.errors.logbook ||
                    this.document_error ||
                    this.form.errors.terms) {
                    return 'bg-red-400 hover:bg-red-400 text-white'
                }
            },
        },
        computed: {
            currentDate() {
                return moment().format('DD MMMM YYYY')
            },
            fullName() {
                return this.form.arrested_firstname + ' ' + this.form.arrested_middlename + ' ' + this.form.arrested_lastname + ' ' + this.form.arrested_suffix
            }
        }
    })
</script>

<style>
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
</style>
