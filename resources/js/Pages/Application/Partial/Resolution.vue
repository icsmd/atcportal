<template>
    <div class="border bg-white overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <div class="p-4 bg-cyan-500 text-white font-extrabold ">
            Resolution
        </div>
        <div v-if="!resolution.file" class="px-4 py-6 sm:px-6">
            <div>
                <a :href="draft" target="_blank" class="btn btn-primary">Download Resolution Draft</a>
            </div>
            <div class="mt-2">
                <label for="resolution_file" class="form-label"> Resolution<span style="color: red">*</span> </label>
                <input type="file" id="resolution_file" @input="form.resolution_file = $event.target.files[0]" class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 px-3 py-1.5" />
                <jet-input-error :message="form.errors.resolution_file" class="mt-2" />
            </div>
            <div class="mt-2">
                <label class="form-label"> Attachments</label>
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
                maxParallelUploads="2"
                />
                <jet-input-error :message="form.errors.temporary_documents" class="mt-2" />
            </div>
        </div>
        <div v-if="!resolution.file" class="px-4 py-4 bg-gray-50 text-right sm:px-6">
            <button type="submit" class="btn btn-success" @click="upload" :class="{ 'opacity-25': form.processing || uploadProgress }" :disabled="form.processing || uploadProgress">Upload</button>
        </div>
        <div v-if="resolution.file" class="p-4">
            <div>
                <div>
                    <label class="block font-bold ">Date: </label>
                    <span>{{resolution.date}}</span>
                </div>
                <div class="mt-2">
                    <a :href="resolution.file" target="_blank" class="inline-flex text-blue-600  no-underline hover:underline cursor-pointer">Final Resolution
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                      </svg>
                    </a>
                </div>
                <div v-if="resolution.attachments.length != 0" class="mt-2">
                    <label class="block font-bold ">Attachments: </label>
                    <div v-for="(document,index) in resolution.attachments">
                        <a :href="document.original_url" target="_blank" class="inline-flex text-blue-600  no-underline hover:underline cursor-pointer">{{document.name + '.' + document.extension}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                          </svg>
                        </a>
                    </div>
                </div>                            
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
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
        components: {
            FilePond,
            JetInputError,
        },
        props: ['draft','id','resolution'],
        data() {
            return {
                server: {
                    process: route('upload.temporary'),
                    revert: route('upload.temporary.destroy'),
                    headers: {'X-CSRF-TOKEN': this.$page.props.csrf_token },
                }, 
                form: this.$inertia.form({
                    resolution_file: '',
                    temporary_documents: [],
                }),
                documents: [],
                uploadProgress: false
            }
        },
        methods: {
            upload() {
                this.form.post(route('applications.resolution',{application:this.id}),{
                    onSuccess: () => this.form.reset(),
                })
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