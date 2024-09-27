<template>
    <Modal :show="show" @close="close">
        <template #title>
            Approve Application
        </template>
        <template #content>
            <div>
                <label for="remarks" class="form-label"> Remarks<span style="color: red">*</span> </label>
                <textarea id="remarks" :class="changeClassIfError(form.errors.remarks)" v-model="form.remarks"></textarea>
                <jet-input-error :message="form.errors.remarks" class="mt-2" />
            </div>
            <div class="mt-2">
                <label class="form-label"> Attachments<span style="color: red">*</span> </label>
                <file-pond
                name="document"
                ref="pond"
                label-idle="Drop files here..."
                :allow-multiple="true"
                :server="server"
                :files="[...documents]"
                max-files="10"
                @processfile="onProcessFile"
                @removefile="onRemoveFile"
                @updatefiles="onUpdateFiles"
                @processfiles="onProcessFiles"
                @processfilestart="onProcessFileStart"
                maxFileSize="1000MB"
                maxParallelUploads="1"
                />
                <jet-input-error :message="form.errors.temporary_documents" class="mt-2" />
            </div>
        </template>
        <template #footer>
            <button class="btn btn-secondary" @click="close">Cancel</button>
            <button class="ml-4 btn btn-success" @click="submit" :class="{ 'opacity-25': form.processing || uploadProgress }" :disabled="form.processing || uploadProgress">Approve</button>
        </template>
    </Modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import Modal from '@/Components/Modal.vue'
    import JetInputError from '@/Jetstream/InputError.vue'

    import vueFilePond from "vue-filepond"
    import "filepond/dist/filepond.min.css"
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
    import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"

    const FilePond = vueFilePond(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    )

    export default defineComponent({
        emits: ['close'],
        components: {
            FilePond,
            JetInputError,
            Modal
        },
        data() {
            return {
                server: {
                    process: route('upload.temporary'),
                    revert: route('upload.temporary.destroy'),
                    headers: {'X-CSRF-TOKEN': this.$page.props.csrf_token },
                }, 
                form: this.$inertia.form({
                    remarks: '',
                    temporary_documents: [],
                }),
                documents: [],
                uploadProgress: false
            }
        },
        props: {
            show: {
                default: false
            },
            id: {
                default: null
            },
        },
        methods: {
            close() {
                this.$emit('close')
            },
            onRemoveFile(error, file) {
                if (error) {
                    this.form.setError('temporary_documents', 'Ooopps! Something went wrong.')
                    return
                }

                let index = this.form.temporary_documents.indexOf(file.serverId)
                this.form.temporary_documents.splice(index,1)
            },
            onProcessFile(error, file){
                if (error) {
                    this.form.setError('temporary_documents', 'Ooopps! Something went wrong.')
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
            submit() {
                this.form.post(route('applications.approve',{application:this.id}),{
                    onSuccess: () => this.close(),
                    onFinish: () => this.form.reset(),
                })
            },
            changeClassIfError(errorMessage) {
                return {
                    'block': true,
                    'w-full': true,
                    'focus:outline-none': true,
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