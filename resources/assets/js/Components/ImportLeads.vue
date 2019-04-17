<style>
    .v-select {
        width: 100% !important;
    }
</style>

<template>
    <div>
        <form @submit.prevent="updateMappedFields" v-if="!data.file.fields_mapped_at">
            <div class="u-flex u-justify-center ">
                <dl class="u-flex u-width-75 u-pv-small u-border-bottom">
                    <dt class="u-flex u-width-50">File Column</dt>

                    <dd class="u-flex u-width-50">Custom Field</dd>
                </dl>
            </div>

            <div class="u-flex u-justify-center "
                 v-for="heading in data.headings"
                 :key="heading.id"
            >
                <dl class="u-flex u-width-75 u-pv-small u-border-bottom">
                    <dt class="u-flex u-width-50">
                        {{ heading.name }}
                    </dt>

                    <dd class="u-flex u-width-50">
                        <v-select maxHeight="200px"
                                  v-model="heading.custom_field_id"
                                  :options="options"
                        ></v-select>
                    </dd>
                </dl>
            </div>

            <div class="u-flex u-justify-center u-mt-small">
                <div class="u-width-75" v-for="error in utilities.errors">
                    <small class="c-field__message u-color-danger">
                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                    </small>
                </div>
            </div>

            <div class="u-flex u-justify-center u-mt-small">
                <div class="u-width-75">
                    <button type="submit"
                            class="c-btn c-btn--blue pull-right"
                    >
                        Import
                    </button>
                </div>
            </div>
        </form>

        <div class="row" v-if="data.file.fields_mapped_at && data.file.processed_leads < data.file.total">
            <div class="col-sm-12 u-text-center">
                <h4>Validating File</h4>

                <div class="fa-4x">
                    <i class="fa fa-spinner fa-pulse u-color-info"></i>
                </div>

                <p class="u-text-small">Please wait. This may take some time</p>
                <p class="u-text-xsmall">Feel free to close this window and come back to this page later</p>
            </div>
        </div>

        <div v-if="data.file.fields_mapped_at && data.file.processed_leads >= data.file.total && !data.file.import_started_at">
            <div class="u-flex u-justify-center u-mb-small">
                <div class="u-flex ">
                    <h4>File Validated</h4>
                </div>
            </div>

            <div class="u-flex u-justify-center u-mb-small">
                <div class="u-block u-mr-xsmall u-text-center">
                    <h4 class="u-block">{{ file.valid_leads }}</h4>

                    <p class="u-block">Valid Leads </p>
                </div>

                <div class="u-block  u-ml-xsmall u-text-center">
                    <h4 class="u-block">{{ data.file.invalid_leads }}</h4>

                    <p class="u-block">Invalid Leads </p>
                </div>
            </div>

            <div class="u-flex u-justify-center">
                <div class="u-flex">
                    <button type="submit"
                            class="c-btn c-btn--blue u-mt-small u-mr-xsmall"
                            @click="importLeads"
                            v-if="data.file.valid_leads > 0"
                    >
                        Import File
                    </button>

                    <button type="submit"
                            class="c-btn c-btn--danger u-mt-small u-ml-xsmall"
                            @click="discardLeads"
                    >
                        Discard File
                    </button>
                </div>
            </div>
        </div>

        <div class="row" v-if="data.file.import_started_at && data.file.imported_leads < data.file.valid_leads">
            <div class="col-sm-12 u-text-center">
                <h4>Importing File</h4>

                <div class="fa-4x">
                    <i class="fa fa-spinner fa-pulse u-color-info"></i>
                </div>

                <p class="u-text-small">Please wait. This may take some time</p>
                <p class="u-text-xsmall">Feel free to close this window and come back to this page later</p>
            </div>
        </div>

        <div v-if="data.file.import_started_at && data.file.imported_leads >= data.file.valid_leads">
            <div class="u-flex u-justify-center u-mb-small">
                <div class="u-flex ">
                    <i class="fa fa-check fa-5x u-text-success"></i>
                </div>
            </div>

            <div class="u-flex u-justify-center u-mb-small">
                <div class="u-flex ">
                    <button class="c-btn c-btn--blue u-mt-small"
                            @click="emitter('complete')"
                    >
                        Leads Imported
                    </button>
                </div>
            </div>
        </div>
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
            file: {
                type: Object,
                required: true,
            },
        },
        filters: {},
        computed: {
            options() {
                let self = this

                return collect(this.custom_fields).map(function (field) {
                    if (!self.isSelected(field)) {
                        return {
                            label: field.name,
                            value: field.id,
                        }
                    }
                }).push({
                    label: 'Ignore Field',
                    value: null,
                })
                    .filter()
                    .toArray()
            },
        },
        data() {
            return {
                data: {
                    file: {},
                    headings: [],
                },
                utilities: {
                    cancelToken: null,
                    axiosSource: null,
                    errors: null,
                },
            }
        },
        methods: {
            importLeads() {
                let self = this

                self.utilities.errors = null

                axios.post('/api/copy-temporary-leads/' + self.data.file.id)
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.data.file = response.data.file

                        const client = axios.create({})

                        axiosRetry(client, {
                            retries: 999999,
                            retryCondition: self.retryCondition,
                            retryDelay: self.retryDelay,
                        })

                        return client.post('/api/copy-temporary-leads/' + self.data.file.id + '/check-status', {}, {
                            cancelToken: self.utilities.axiosSource.token,
                        })
                    })
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.data.file = response.data.file
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            discardLeads() {
                let self = this

                axios.post('/api/import-leads/' + this.file.id + '/discard-file')
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.emitter('discarded')

                        self.data.file = response.data.file
                    })
            },
            updateMappedFields() {
                let self = this

                axios.post('/api/map-fields/' + this.file.id, this.data.headings)
                    .then(function (response) {
                        self.emitter('update', response.data)

                        self.data.file = response.data

                        return axios.post('/api/import-leads/' + self.data.file.id)
                    })
                    .then(function (response) {
                        const client = axios.create({})

                        axiosRetry(client, {
                            retries: 999999,
                            retryCondition: self.retryCondition,
                            retryDelay: self.retryDelay,
                        })

                        return client.post('/api/import-leads/' + self.data.file.id + '/check-status', {}, {
                            cancelToken: self.utilities.axiosSource.token,
                        })
                    })
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.data.file = response.data.file
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            isSelected(heading) {
                return collect(this.data.headings).map(function (current) {
                    if (current.custom_field_id && current.custom_field_id.value === heading.id) {
                        return true
                    }
                })
                    .filter()
                    .count() > 0
            },
            retryCondition(error) {
                return true
            },
            retryDelay(retryCount) {
                return 4000
            },
            mapHeadings() {
                let self = this

                return collect(self.data.file.headings).map(function (heading) {
                    let data = collect(self.custom_fields).map(function (field) {
                        if (heading.slug === field.slug) {
                            return {
                                label: field.name,
                                value: field.id,
                            }
                        }
                    }).filter()

                    if (data.count() > 0) {
                        heading.custom_field_id = data.first()
                    } else {
                        heading.custom_field_id = {
                            label: 'Ignore Field',
                            value: null,
                        }
                    }

                    return heading
                })
                    .toArray()
            },
            emitter(event, data) {
                this.$emit(event, data)
            },
        },
        created() {
            let self = this

            this.data.file = this.file

            this.data.headings = this.mapHeadings()

            this.utilities.axiosSource = axios.CancelToken.source()

            if (this.data.file.fields_mapped_at && (this.data.file.processed_leads < this.data.file.total)) {
                self.emitter('validating')

                const client = axios.create({})

                axiosRetry(client, {
                    retries: 999999,
                    retryCondition: self.retryCondition,
                    retryDelay: self.retryDelay,
                })

                client.post('/api/import-leads/' + self.file.id + '/check-status', {}, {
                    cancelToken: self.utilities.axiosSource.token,
                })
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.data.file = response.data.file
                    })
            }

            if (this.data.file.import_started_at && (this.data.file.imported_leads < this.data.file.valid_leads)) {
                self.emitter('validating')

                const client = axios.create({})

                axiosRetry(client, {
                    retries: 999999,
                    retryCondition: self.retryCondition,
                    retryDelay: self.retryDelay,
                })

                client.post('/api/copy-temporary-leads/' + self.file.id + '/check-status', {}, {
                    cancelToken: self.utilities.axiosSource.token,
                })
                    .then(function (response) {
                        self.emitter('update', response.data.file)

                        self.data.file = response.data.file
                    })
            }
        },
    }
</script>

