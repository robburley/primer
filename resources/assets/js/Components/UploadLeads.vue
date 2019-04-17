<style>
    .clickable {
        cursor: pointer;
    }
    
    .c-modal__dialog {
        margin: 0;
    }

    .v--modal-overlay {
        background-color: rgba(29, 37, 49, 0.9)
    }

    .tooltip {
        padding: 4px;
        border-radius: 4px;
        background-color: #2EA1FB;
        color: #ffffff;
        margin-right: 5px;
    }
</style>

<template>
    <div>
        <div class="c-toolbar u-mb-medium">
            <button class="c-sidebar-toggle u-mr-small">
                <span class="c-sidebar-toggle__bar"></span>
                <span class="c-sidebar-toggle__bar"></span>
                <span class="c-sidebar-toggle__bar"></span>
            </button>

            <h3 class="c-toolbar__title">Upload Leads</h3>

            <button type="button"
                    class="c-btn c-btn--blue u-ml-auto"
                    @click.prevent="showUploadLeadsModal"
            >
                Upload File
            </button>
        </div>

        <div class="container">
            <div class="row" v-if="runningFiles.length > 0">
                <div class="col-sm-12">
                    <div class="c-table-responsive@desktop">
                        <table class="c-table c-table--zebra u-mb-small">
                            <caption class="c-table__title">
                                Files currently processing
                            </caption>
                            <thead class="c-table__head">
                                <tr class="c-table__row">
                                    <th class="c-table__cell c-table__cell--head">Name</th>
                                    <th class="c-table__cell c-table__cell--head">Total</th>
                                    <th class="c-table__cell c-table__cell--head">Processed</th>
                                    <th class="c-table__cell c-table__cell--head">Invalid Leads</th>
                                    <th class="c-table__cell c-table__cell--head">Imported</th>
                                    <th class="c-table__cell c-table__cell--head">Uploaded</th>
                                    <th class="c-table__cell c-table__cell--head"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="c-table__row"
                                    v-for="file in orderBy(runningFiles, 'created_at', -1)"
                                    :key="file.id"
                                    v-if="data.files.length > 0"
                                >
                                    <td class="c-table__cell">
                                        {{ file.name }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.total }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.processed_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.invalid_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.imported_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.created_at | moment('DD/MM/YYYY H:mm') }}
                                    </td>

                                    <td class="c-table__cell u-text-right">
                                        <a class="c-btn c-btn--secondary pull-right"
                                           href="#"
                                           v-if="!file.error_at && file.processed_leads < file.total"
                                           @click="showImportLeadsModal(file)"
                                        >
                                            Validate
                                        </a>

                                        <a class="c-btn c-btn--blue pull-right"
                                           href="#"
                                           v-if="file.processed_leads >= file.total && file.imported_leads < file.valid_leads && !file.error_at"
                                           @click="showImportLeadsModal(file)"
                                        >
                                            Import
                                        </a>

                                        <div v-if="file.import_started_at && file.imported_leads >= file.valid_leads && !file.error_at">
                                            <p class="u-text-success pull-right"
                                            >
                                                Complete
                                            </p>
                                        </div>

                                        <p class="u-text-danger"
                                           v-if="file.error_at"
                                        >
                                            {{ file.error_text }}
                                        </p>
                                    </td>
                                </tr>

                                <tr class="c-table__row" v-else>
                                    <td class="c-table__cell" colspan="5">There are no files</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="c-card u-p-medium u-mb-small">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="c-field">
                                    <label class="c-field__label">File Name</label>
                                    <input class="c-input"
                                           type="text"
                                           placeholder="File Name"
                                           v-model="data.search.name"
                                    >
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="c-field">
                                    <label class="c-field__label">Status</label>

                                    <v-select maxHeight="200px"
                                              v-model="data.search.status"
                                              :options="utilities.statuses"
                                    ></v-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="c-table-responsive@desktop">
                        <table class="c-table c-table--zebra u-mb-small">
                            <thead class="c-table__head">
                                <tr class="c-table__row">
                                    <th class="c-table__cell c-table__cell--head">Name</th>
                                    <th class="c-table__cell c-table__cell--head">Total</th>
                                    <th class="c-table__cell c-table__cell--head">Processed</th>
                                    <th class="c-table__cell c-table__cell--head">Invalid Leads</th>
                                    <th class="c-table__cell c-table__cell--head">Imported</th>
                                    <th class="c-table__cell c-table__cell--head">Uploaded</th>
                                    <th class="c-table__cell c-table__cell--head"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="c-table__row"
                                    v-for="file in orderBy(filteredFiles, 'created_at', -1)"
                                    :key="file.id"
                                    v-if="data.files.length > 0"
                                >
                                    <td class="c-table__cell">
                                        {{ file.name }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.total }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.processed_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.invalid_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.imported_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ file.created_at | moment('DD/MM/YYYY H:mm') }}
                                    </td>

                                    <td class="c-table__cell u-text-right">
                                        <a class="u-ml-xsmall pull-right"
                                           :href="'/settings/upload-leads/' + file.id + '/invalid-leads'"
                                           v-tooltip.right-end="{ content: 'Download Invalid leads', classes: 'tooltip' }"
                                           v-if="file.invalid_leads > 0"
                                        >
                                            <i class="fa fa-download"></i>
                                        </a>

                                        <a class="c-btn c-btn--secondary pull-right"
                                           href="#"
                                           v-if="!file.error_at && file.processed_leads < file.total"
                                           @click="showImportLeadsModal(file)"
                                        >
                                            Validate
                                        </a>

                                        <a class="c-btn c-btn--blue pull-right"
                                           href="#"
                                           v-if="file.processed_leads >= file.total && file.imported_leads < file.valid_leads && !file.error_at"
                                           @click="showImportLeadsModal(file)"
                                        >
                                            Import
                                        </a>

                                        <span class="u-text-success pull-right"
                                              v-if="file.import_started_at && file.imported_leads >= file.valid_leads && !file.error_at"
                                        >
                                            Complete
                                        </span>

                                        <span class="u-text-danger"
                                              v-if="file.error_at"
                                        >
                                            {{ file.error_text }}
                                        </span>
                                    </td>
                                </tr>

                                <tr class="c-table__row" v-else>
                                    <td class="c-table__cell" colspan="5">There are no files</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <modal name="upload-leads-modal"
               :adaptive="true"
               height="auto"
               :pivotY="0.1"
               classes=""
               width="645"
               :scrollable="true"
               @closed="hideUploadLeadsModal"
        >
            <div class="u-m-small" role="document">
                <div class="c-modal__content">
                    <div class="c-modal__header">
                        <h3 class="c-modal__title">
                            Upload Leads
                        </h3>

                        <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close u-text-danger clickable pull-right" @click="hideUploadLeadsModal"></i>
                        </span>
                    </div>

                    <div class="c-modal__body">
                        <form @submit.prevent="storeFile" enctype="multipart/form-data">
                            <div class="row" :class="{'u-hidden': utilities.uploaded || utilities.uploading}">
                                <div class="c-field u-mb-small col-lg-12">
                                    <label class="c-field__label">
                                        Choose a CSV file to upload <br>

                                        <small>Maximum file size: 40mb</small>
                                    </label>

                                    <file-upload
                                            target="/api/upload-file"
                                            action="POST"
                                            classes="c-input"
                                            @started="uploadStarted"
                                            @complete="uploadComplete"
                                            @error="uploadError"
                                    ></file-upload>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" v-for="error in utilities.errors">
                                    <small class="c-field__message u-color-danger">
                                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                                    </small>
                                </div>
                            </div>
                        </form>

                        <div class="row" v-if="utilities.uploading">
                            <div class="col-sm-12 u-text-center">
                                <h4>Uploading File</h4>

                                <div class="fa-4x">
                                    <i class="fa fa-spinner fa-pulse u-color-info"></i>
                                </div>

                                <p>Please wait</p>
                            </div>
                        </div>

                        <div class="row" v-if="utilities.analysing">
                            <div class="col-sm-12 u-text-center">
                                <h4>Analysing File</h4>

                                <div class="fa-4x">
                                    <i class="fa fa-spinner fa-pulse u-color-info"></i>
                                </div>

                                <p>Please wait</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </modal>

        <modal name="import-leads-modal"
               :adaptive="true"
               height="auto"
               :pivotY="0.1"
               classes=""
               width="930"
               :scrollable="true"
        >
            <div class="u-m-small" role="document">
                <div class="c-modal__content">
                    <div class="c-modal__header">
                        <h3 class="c-modal__title">
                            Import Leads
                        </h3>

                        <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close u-text-danger clickable pull-right" @click="hideImportLeadsModal"></i>
                        </span>
                    </div>

                    <div class="c-modal__body">
                        <import-leads :file="data.current_file"
                                      @update="updateFile"
                                      @discarded="hideImportLeadsModal"
                                      @complete="hideImportLeadsModal"
                                      :custom_fields="custom_fields"
                        ></import-leads>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        components: {},
        props: {
            custom_fields: {
                type: Array,
                required: true,
            },
            files: {
                required: true,
                type: Array,
            },
        },
        filters: {},
        computed: {
            runningFiles() {
                let self = this

                return collect(self.data.files)
                    .filter(function (file, key) {
                        return file.running === 1
                    })
                    .toArray()
            },
            filteredFiles() {
                let self = this

                return collect(self.data.files)
                    .filter(function (file, key) {
                        let conditional = true

                        if (self.data.search.name && conditional) {
                            conditional = file.name.includes(self.data.search.name)
                        }

                        if (self.data.search.status && conditional) {
                            conditional = file.status == self.data.search.status.value
                        }

                        return conditional
                    })
                    .toArray()
            },
        },
        data() {
            return {
                data: {
                    files: [],
                    current_file: null,
                    search: {
                        name: '',
                        status: null,
                    },
                },
                utilities: {
                    errors: null,
                    uploading: false,
                    uploaded: false,
                    analysing: false,
                    complete: false,
                    cancelToken: null,
                    axiosSource: null,
                    statuses: [
                        {value: 1, label: 'Uploaded'},
                        {value: 2, label: 'Validated'},
                        {value: 3, label: 'Complete'},
                        {value: 4, label: 'Error'},
                    ],
                },
            }
        },
        methods: {
            indexWhere(array, conditionFn) {
                const item = array.find(conditionFn)

                return array.indexOf(item)
            },
            updateFile(data) {
                this.data.current_file = data

                let index = this.indexWhere(this.data.files, o => o.id === this.data.current_file.id)

                Vue.set(this.data.files, index, this.data.current_file)
            },
            showImportLeadsModal(file) {
                this.data.current_file = file

                this.$modal.show('import-leads-modal')
            },
            hideImportLeadsModal() {
                this.$modal.hide('import-leads-modal')
            },
            showUploadLeadsModal() {
                this.$modal.show('upload-leads-modal')
            },
            hideUploadLeadsModal() {
                this.resetUpload()

                this.$modal.hide('upload-leads-modal')
            },
            resetUpload() {
                this.utilities.errors = null
                this.utilities.uploading = false
                this.utilities.uploaded = false
                this.utilities.analysing = false
                this.utilities.complete = false

                this.utilities.axiosSource.cancel('Operation cancelled by the user.')

                let CancelToken = axios.CancelToken

                this.utilities.axiosSource = CancelToken.source()
            },
            uploadStarted() {
                this.utilities.uploading = true

                this.utilities.errors = null
            },
            uploadComplete(e) {
                this.updateFile(e.file)

                this.utilities.uploaded = true

                this.utilities.uploading = false

                this.analyseFile()
            },
            uploadError(e) {
                this.utilities.errors = e

                this.utilities.uploading = false
            },
            analyseFile() {
                let self = this

                this.utilities.analysing = true

                const client = axios.create({})

                axiosRetry(client, {
                    retries: 999999,
                    retryCondition: this.retryCondition,
                    retryDelay: this.retryDelay,
                })

                client.post('/api/upload-file/' + self.data.current_file.id, {}, {
                    cancelToken: self.utilities.axiosSource.token,
                })
                    .then(function (response) {
                        self.utilities.complete = true

                        self.updateFile(response.data.file)

                        self.data.files.push(self.data.current_file)

                        self.hideUploadLeadsModal()
                    })
            },
            retryCondition(error) {
                return true
            },
            retryDelay(retryCount) {
                return 4000
            },
        },
        mounted() {
            this.data.files = this.files

            let CancelToken = axios.CancelToken

            this.utilities.axiosSource = CancelToken.source()
        },
    }
</script>

