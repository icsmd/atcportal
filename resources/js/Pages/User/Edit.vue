<template>
    <app-layout title="Edit User">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    <div>
                        <label for="name" class="form-label"> Name<span style="color: red">*</span> </label>
                        <input-validation type="text" id="name" v-model="form.name" :errorMessage="form.errors.name" placeholderValue="Enter name" />
                    </div>
                    <div class="mt-2">
                        <label for="email" class="form-label"> Email<span style="color: red">*</span> </label>
                        <input-validation type="email" id="email" v-model="form.email" :errorMessage="form.errors.email" placeholderValue="Enter email" />
                    </div>
                    <div class="mt-2">
                        <label for="tel" class="form-label"> Tel<span style="color: red">*</span> </label>
                        <input-validation type="text" id="tel" v-model="form.tel" :errorMessage="form.errors.tel" placeholderValue="+639XXXXXXXXX" />
                    </div>
                    <div class="mt-2">
                        <label for="password" class="form-label"> Password(optional)</label>
                        <input-validation type="password" id="password" v-model="form.password" :errorMessage="form.errors.password" placeholderValue="Enter password" />
                    </div>
                    <div class="mt-2">
                        <label for="active" class="form-label"> Active<span style="color: red">*</span> </label>
                        <Switch v-model="form.active" id="active" :class="[form.active ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                            <span class="sr-only">Use setting</span>
                            <span aria-hidden="true" :class="[form.active ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                        </Switch>
                    </div>
                    <div class="mt-2">
                        <label for="groups" class="form-label"> Groups Template</label>
                        <select id="groups" v-model="form.groups" name="groups" class="mt-1 block w-full pl-3 pr-10 py-2  border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500  rounded-md" @change="onSelectChange">
                            <option value="0">Applicant</option>
                            <option value="1">ATC Secretariat</option>
                            <option value="2">Review Committee</option>
                            <option value="3">Council</option>
                            <option value="4">Custom</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="groups" class="form-label"> Permissions<span style="color: red">*</span></label>
                        <fieldset class="mt-4">
                            <div v-for="permission in permissions" :key="permission" class="flex space-x-4">
                                <div class="flex items-center">
                                    <input :id="permission" type="checkbox" :value="permission" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" v-model="form.permissions" @change="onChangeCheck"/>
                                    <label :for="permission" class="ml-2 block  font-medium text-gray-700">
                                        {{permission}}
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <jet-input-error :message="form.errors.permissions" class="mt-2" />
                    </div>
                </div>
                <!-- Footer -->
                <div class="px-4 py-4 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="btn btn-primary" @click="update" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Update</button>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { Link } from '@inertiajs/vue3'
    import InputValidation from '@/Components/InputValidation.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import { Switch } from '@headlessui/vue'

    export default defineComponent({
        components: {
            AppLayout,
            InputValidation,
            JetInputError,
            Link,
            Switch
        },
        props: ['selected_user','permissions','user_permissions'],
        mounted() {
            this.$nextTick(function () {
                this.onChangeCheck()
            })
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: this.selected_user.name,
                    email: this.selected_user.email,
                    tel: this.selected_user.tel,
                    password: '',
                    active: this.selected_user.active,
                    groups: '',
                    permissions: this.user_permissions
                }),
                groups: [
                    [
                        'send application',
                        'update application',
                        'restrict view other application',
                        'edit narrative',
                    ],
                    [
                        'approve application',
                        'update application',
                        'disapprove application',
                        'provide resolution',
                        'view discussion',
                        'view vote'
                    ],
                    [
                        'comment application',
                        'endorse application',
                        'view discussion',
                    ],
                    [
                        'comment application',
                        'vote application',
                        'view discussion',
                        'view vote',
                    ],
                ]
            }
        },
        methods: {
            update() {
                this.form.put(route('users.update',{user:this.selected_user.id}),{
                    onSuccess: () => this.close(),
                    onFinish: () => this.form.reset(),
                })
            },
            onSelectChange() {
                if (this.form.groups == '4')
                    return

                let permissions = this.groups[this.form.groups]
                this.form.permissions = permissions
            },
            onChangeCheck() {
                let index = 4;
                this.groups.map((group,groupdIdx) => {
                    if (this.arrayEquals(group.sort(),this.form.permissions.sort()))
                        index = groupdIdx
                })

                this.form.groups = index
            },
            arrayEquals(a, b) {
                return Array.isArray(a) &&
                Array.isArray(b) &&
                a.length === b.length &&
                a.every((val, index) => val === b[index]);
            }
        },
    })
</script>
