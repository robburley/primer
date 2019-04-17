<style>
    .v-select .selected-tag {
        position: absolute;
        font-size: 14px;
    }

    .dropdown-menu {
        font-size: 14px;
    }

    .v-select .dropdown-toggle {
        border: 1px solid #dfe3e9;
        color: #354052;
    }

    .v-select .dropdown-toggle .clear {
        bottom: 6px;
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
    <div class="row">
        <div class="col-lg-12 u-text-center u-mb-small">
            <h3>{{ data.totalRecords }}</h3>
            <h4>Total Available Records</h4>

            <v-popover placement="left-start" style="position: absolute; top:0;">
                <button class="c-btn c-btn--secondary">
                    Information
                </button>

                <template slot="popover" class="u-mh-small">
                    <div class="u-p-small">
                        <p><strong>Dates - </strong> for dates to work, they will need to be in dd/mm/yyyy format, e.g. 01/01/2018</p>
                        <p><strong>Between Operator - </strong> Required 2 values with a comma between them. e.g. 1,2 or 01/01/2018,03/01/2018</p>
                        <p><strong>Value Is In Operator - </strong> Required values with a comma between each. e.g. 1,2,3</p>
                        <p><strong>Contains - </strong> This will search for for a partial value in the field. </p>
                        <p>e.g. Searching for "a" in the name field will return all names containing that letter.</p>
                    </div>

                    <a v-close-popover class="c-btn c-btn--secondary pull-right">Close</a>
                </template>
            </v-popover>
        </div>

        <div class="col-lg-12" v-show="utilities.errors">
            <div v-for="error in utilities.errors">
                <small class="c-field__message u-color-danger">
                    <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                </small>
            </div>
        </div>

        <div class="col-lg-12">
            <span class="c-divider u-mb-small"></span>
        </div>

        <div class="col-lg-12">
            <div v-for="scope in data.scopes">
                <div class="row">
                    <div class="col-11 col-lg-11">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="c-field__label">Field</label>

                                <v-select maxHeight="115px"
                                          v-model="scope.custom_field_id"
                                          :options="fieldOptions"
                                          @search:focus="scopeEdited(scope)"
                                ></v-select>
                            </div>

                            <div class="col-lg-4">
                                <label class="c-field__label">Operator</label>

                                <v-select maxHeight="115px"
                                          v-model="scope.operator"
                                          :options="orderBy(utilities.operators, 'label')"
                                          @search:focus="scopeEdited(scope)"
                                ></v-select>
                            </div>

                            <div class="col-lg-4">
                                <label class="c-field__label">Value</label>

                                <input class="c-input" type="text" v-model="scope.value" @change="scope.editing = 1">
                            </div>
                        </div>
                    </div>

                    <div class="col-1 col-lg-1">
                        <div class="row">
                            <div class="col-12">
                                <i class="fa fa-close clickable pull-right u-text-danger"
                                   v-tooltip.right-end="{ content: 'Remove Scope', classes: 'tooltip' }"
                                   @click="removeScope(scope)"
                                ></i>
                            </div>
                        </div>

                        <div class="row u-mt-medium">
                            <div class="col-12">
                                <i class="fa fa-save clickable pull-right u-text-success"
                                   v-if="scope.editing === 1"
                                   v-tooltip.right-end="{ content: 'Save Scope', classes: 'tooltip' }"
                                   @click="saveScope(scope)"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <span class="c-divider u-mb-small u-mt-medium"></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-12 u-mt-small">
            <a class="c-btn c-btn--blue pull-right" @click.prevent="addScope">
                Add Scope
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        components: {},
        props: {
            campaign: {
                required: true,
                type: Object,
            },
            groups: {
                required: true,
                type: Array,
            },
        },
        filters: {},
        computed: {
            fieldOptions() {
                return collect(this.groups)
                    .pluck('custom_fields')
                    .flatten(1)
                    .map(function (group) {
                        return {
                            label: group.name,
                            value: group.id,
                        }
                    }).toArray()
            },
        },
        data() {
            return {
                data: {
                    scopes: [],
                    totalRecords: 0,
                },
                utilities: {
                    operators: [
                        {
                            label: 'Greater Than (Number)',
                            value: '>',
                        },
                        {
                            label: 'Less Than (Number)',
                            value: '<',
                        },
                        {
                            label: 'Greater Than Or Equals (Number)',
                            value: '>=',
                        },
                        {
                            label: 'Less Than Or Equals (Number)',
                            value: '<=',
                        },
                        {
                            label: 'Equal To',
                            value: '=',
                        },
                        {
                            label: 'Not Equal To',
                            value: '<>',
                        },
                        {
                            label: 'Value Is In',
                            value: 'in',
                        },
                        {
                            label: 'Contains',
                            value: 'LIKE',
                        },
                        {
                            label: 'Between',
                            value: 'between',
                        },
                    ],
                    errors: null,
                },
            }
        },
        methods: {
            updateCampaginCount() {
                let self = this

                axios.post('/api/campaigns/' + self.campaign.id + '/available-records/')
                    .then(function (response) {
                        self.data.totalRecords = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            scopeEdited(scope) {
                scope.editing = 1
            },
            saveScope(scope) {
                let self = this

                self.utilities.errors = null

                let index = this.data.scopes.indexOf(scope)

                let baseUrl = '/api/campaigns/' + this.campaign.id + '/scopes/'

                let url = scope.id
                    ? baseUrl + scope.id
                    : baseUrl

                axios.post(url, scope)
                    .then(function (response) {
                        let newScope = response.data

                        newScope.operator = collect(self.utilities.operators).firstWhere('value', newScope.operator)

                        newScope.custom_field_id = collect(self.fieldOptions).firstWhere('value', newScope.custom_field_id)

                        newScope.editing = 0

                        Vue.set(self.data.scopes, index, response.data)

                        scope = response.data

                        self.updateCampaginCount()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            addScope() {
                this.data.scopes.push({
                    'custom_field_id': null,
                    'operator': null,
                    'value': null,
                    'editing': 1,
                })
            },
            removeScope(scope) {
                let self = this

                self.utilities.errors = null

                let index = self.data.scopes.indexOf(scope)

                self.data.scopes.splice(index, 1)

                if (scope.id) {
                    axios.delete('/api/campaigns/' + self.campaign.id + '/scopes/' + scope.id)
                        .then(function (response) {
                            self.updateCampaginCount()
                        })
                        .catch(function (error) {
                            if (error.response && error.response.status === 422) {
                                self.utilities.errors = error.response.data.errors
                            }
                        })
                }
            },
        },
        mounted() {
            let self = this

            self.updateCampaginCount()

            self.data.scopes = collect(self.campaign.scopes)
                .map(function (scope) {
                    scope.operator = collect(self.utilities.operators).firstWhere('value', scope.operator)

                    scope.custom_field_id = collect(self.fieldOptions).firstWhere('value', scope.custom_field_id)

                    scope.editing = 0

                    return scope
                }).toArray()


        },
    }
</script>

