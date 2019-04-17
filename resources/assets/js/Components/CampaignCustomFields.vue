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

    .centered {
        text-align: center;
        vertical-align: middle;
    }
</style>

<template>
    <div>
        <div class="row u-mb-small">
            <div class="col-md-12">
                <a class="c-btn c-btn--secondary u-mr-small" @click.prevent="showGroupModal">
                    New Group
                </a>

                <a class="c-btn c-btn--blue u-ml-small pull-right" @click.prevent="addSelectedGroup">
                    Select Group
                </a>

                <v-select class="pull-right" maxHeight="200px" v-model="data.group_to_add" :options="unselectedGroups"></v-select>

            </div>
        </div>

        <div class="row u-mb-small">
            <div class="col-sm-12">
                <div v-for="error in utilities.deleteErrors">
                    <small class="c-field__message u-color-danger">
                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                    </small>
                </div>

                <draggable v-model="data.selected_groups"
                           :options="{group: {name: 'groups'}}"
                           class="u-bg-light-gray u-p-small u-width-100"
                           @end="updateSelectedGroups"
                >
                    <table class="c-table"
                           v-for="(selectedGroup, index) in data.selected_groups"
                           :key="selectedGroup.id"
                           v-bind:class="{ 'u-mb-small': index !== data.selected_groups.length - 1 }"
                    >
                        <caption class="c-table__title">
                            <div class="row">
                                <div class="col-12">
                                    <i class="fa fa-close clickable u-text-danger pull-right"
                                       @click="deselectGroup(selectedGroup)"
                                       v-tooltip.right-end="{ content: 'Deselect Group', classes: 'tooltip' }"
                                    ></i>
                                </div>
                            </div>

                            <div class="row u-mt-small">
                                <div class="col-12">
                                    <i class="fa fa-sort clickable"></i>

                                    {{ selectedGroup.custom_field_group.name }}

                                    <a class="" href="#!" @click.prevent="showEditGroupModal(selectedGroup.custom_field_group)">
                                        <i class="fa fa-cog"
                                           v-tooltip.right-end="{ content: 'Edit Group', classes: 'tooltip' }"
                                        ></i>
                                    </a>

                                    <a class=""
                                       href="#!"
                                       @click.prevent="deleteGroup(selectedGroup.custom_field_group)"
                                       v-tooltip.right-end="{ content: 'Delete Group', classes: 'tooltip' }"
                                    >
                                        <i class="fa fa-close u-text-danger"></i>
                                    </a>

                                    <a class="c-btn c-btn--secondary pull-right" @click.prevent="showFieldModal(selectedGroup.custom_field_group)">
                                        Add Field
                                    </a>
                                </div>
                            </div>
                        </caption>

                        <thead class="c-table__head c-table__head--slim">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head u-width-30">
                                    Name
                                </th>

                                <th class="c-table__cell c-table__cell--head centered">
                                    <span class="u-hidden-down@wide">Show to </span>Lead Generator
                                </th>

                                <!--<th class="c-table__cell c-table__cell&#45;&#45;head centered">-->
                                    <!--<span class="u-hidden-down@wide">Show to </span>Customer-->
                                <!--</th>-->

                                <th class="c-table__cell c-table__cell--head centered">
                                    <span class="u-hidden-down@wide">Required for </span>Completion
                                </th>

                                <th class="c-table__cell c-table__cell--head"></th>
                            </tr>
                        </thead>

                        <draggable v-model=" selectedGroup.selected_custom_fields"
                                   :options="{group: {name: 'fields'}}"
                                   :element="'tbody'"
                                   @end="updateSelectedGroups"
                        >
                            <tr class="c-table__row" v-for="field in selectedGroup.selected_custom_fields">
                                <td class="c-table__cell">
                                    <i class="fa fa-sort clickable"></i>

                                    {{ field.custom_field.name }}

                                    <span class="u-text-mute">
                                        | <small>{{ field.custom_field.bespoke_form_field.name }}</small>
                                    </span>
                                </td>

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.show_to_lead_generator" @change="updateSelectedGroups">
                                </td>

                                <!--<td class="c-table__cell text-center centered">-->
                                <!--<input type="checkbox" v-model="field.show_to_customers" @change="updateSelectedGroups">-->
                                <!--</td>-->

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.required_for_completion" @change="updateSelectedGroups">
                                </td>

                                <td class="c-table__cell u-pl-zero u-pr-small">
                                    <a class="pull-right" href="#!" @click.prevent="deleteField(selectedGroup.custom_field_group, field.custom_field)">
                                        <i class="fa fa-close u-text-danger"
                                           v-tooltip.right-end="{ content: 'Delete Field', classes: 'tooltip' }"
                                        ></i>
                                    </a>

                                    <a class="pull-right u-mr-xsmall" href="#!" @click.prevent="showEditFieldModal(selectedGroup.custom_field_group, field.custom_field)">
                                        <i class="fa fa-cog"
                                           v-tooltip.right-end="{ content: 'Edit Field', classes: 'tooltip' }"
                                        ></i>
                                    </a>
                                </td>
                            </tr>
                        </draggable>
                    </table>
                </draggable>

                <p class="u-text-danger"> Please note, deleting or editing a field or group on this page will affect the entire system.</p>
            </div>
        </div>

        <modal name="group-modal"
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
                            Field Group Details
                        </h3>

                        <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close u-text-danger clickable pull-right" @click="hideGroupModal"></i>
                        </span>
                    </div>

                    <div class="c-modal__body">
                        <form @submit.prevent="handleGroupForm">
                            <div class="row">
                                <div class="c-field u-mb-small col-lg-12">
                                    <label class="c-field__label">Group Name <span class="u-text-danger">*</span></label>

                                    <input type="text"
                                           class="c-input"
                                           placeholder="Field Name"
                                           v-model="data.current_group.name"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" v-for="error in utilities.errors">
                                    <small class="c-field__message u-color-danger">
                                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                                    </small>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit"
                                            class="c-btn c-btn--blue pull-right"
                                            :disabled="utilities.saving"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </modal>

        <modal name="field-modal"
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
                            Field Details
                        </h3>

                        <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close u-text-danger clickable pull-right" @click="hideFieldModal"></i>
                        </span>
                    </div>

                    <div class="c-modal__body">
                        <form @submit.prevent="handleFieldForm">
                            <div class="row">
                                <div class="c-field u-mb-small col-lg-12">
                                    <label class="c-field__label">Name <span class="u-text-danger">*</span></label>

                                    <input type="text"
                                           class="c-input"
                                           placeholder="Field Name"
                                           v-model="data.current_field.name"
                                           v-if="data.form_type === 'create'"
                                    />

                                    <span v-else>
                                        {{ data.current_field.name }}
                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="c-field__label">Type<span class="u-text-danger">*</span></label>
                                </div>

                                <div class="col-6 col-lg-3 u-p-small " v-for="type in utilities.types" :key="type.id">
                                    <div class="u-p-small u-bordered u-border-rounded u-text-center clickable"
                                         v-bind:class="isBespokeField(type)"
                                         @click="setBespokeField(type)"
                                    >
                                        <h4 v-bind:class="isBespokeField(type)">
                                            <font-awesome :icon="type.icon" v-if="type.icon"></font-awesome>
                                        </h4>

                                        {{ type.name }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="c-field u-mb-small col-lg-6" v-if="this.data.current_field.bespoke_form_field_id && this.data.current_field.bespoke_form_field_id.has_placeholder">
                                    <label class="c-field__label">Placeholder</label>

                                    <input type="text"
                                           class="c-input"
                                           placeholder="Placeholder Text"
                                           v-model="data.current_field.placeholder"
                                    />
                                </div>

                                <div class="c-field u-mb-small col-lg-6" v-if="this.data.current_field.bespoke_form_field_id && this.data.current_field.bespoke_form_field_id.has_default">
                                    <label class="c-field__label">Default</label>

                                    <input type="text"
                                           class="c-input"
                                           placeholder="Default Value"
                                           v-model="data.current_field.default"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="c-field u-mb-small col-lg-12" v-if="this.data.current_field.bespoke_form_field_id && this.data.current_field.bespoke_form_field_id.has_options">
                                    <label class="c-field__label">Options
                                        <small>Add a comma (,) between each option</small>
                                    </label>

                                    <textarea class="c-input"
                                              v-model="data.current_field.options"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="row" v-if="this.data.current_field.bespoke_form_field_id && this.data.current_field.bespoke_form_field_id.has_rules">
                                <div class="col-lg-12">
                                    <label class="c-field__label">
                                        Validation Rules
                                    </label>
                                </div>
                            </div>

                            <div class="row" v-if="this.data.current_field.bespoke_form_field_id && this.data.current_field.bespoke_form_field_id.has_rules">
                                <div class="col-sm-6" v-for="rule in this.data.current_field.bespoke_form_field_id.rules" :key="rule.id">
                                    <div class="c-choice c-choice--checkbox">
                                        <div class="row">
                                            <div class="col-lg-9 col-sm-8">
                                                <input class="c-choice__input"
                                                       :id="'validation' + rule.id"
                                                       name="validations"
                                                       type="checkbox"
                                                       :value="rule.id"
                                                       v-model="data.current_field.validation_rules"
                                                >

                                                <label class="c-choice__label" :for="'validation' + rule.id">
                                                    {{ rule.name }}
                                                </label>

                                                <p class="c-choice__label u-ml-medium" :for="'validation' + rule.id">
                                                    <small>{{ rule.helper_text }}</small>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-4" v-if="rule.has_args && hasRule(rule.id)">
                                                <input type="text"
                                                       class="c-input pull-right"
                                                       placeholder="Value"
                                                       v-model="data.current_field.validation_args[rule.id]"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" v-for="error in utilities.errors">
                                    <small class="c-field__message u-color-danger">
                                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                                    </small>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit"
                                            class="c-btn c-btn--blue pull-right"
                                            :disabled="utilities.saving"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </modal>
    </div>

</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },
        props: {
            groups: {
                required: true,
                type: Array,
            },
            campaign: {
                required: true,
                type: Object,
            },
        },
        computed: {
            unselectedGroups() {
                let self = this

                return self.data.groups
                    .filter(function (group) {
                        return !collect(self.data.selected_groups).pluck('custom_field_group_id').contains(group.id)
                    })
                    .map(function (group) {
                        return {
                            label: group.name,
                            value: group.id,
                        }
                    })
            },
        },
        data() {
            return {
                data: {
                    groups: [],
                    form_type: 'create',
                    current_field: {},
                    current_group: {},
                    selected_groups: [],
                    group_to_add: null,
                },
                utilities: {
                    saving: false,
                    errors: null,
                    deleteErrors: null,
                    types: [],
                    yesNo: [
                        {value: 0, label: 'No'},
                        {value: 1, label: 'Yes'},
                    ],
                },
            }
        },
        methods: {
            showGroupModal() {
                this.data.form_type = 'create'

                this.utilities.errors = null

                this.utilities.deleteErrors = null

                this.resetGroup()

                this.$modal.show('group-modal')
            },
            showEditGroupModal(group) {
                let self = this

                this.utilities.errors = null

                this.utilities.deleteErrors = null

                self.data.form_type = 'edit'

                self.data.current_group = group

                this.$modal.show('group-modal')
            },
            hideGroupModal() {
                this.$modal.hide('group-modal')
            },
            resetGroup() {
                this.utilities.saving = false

                this.data.current_group = {
                    id: '',
                    name: '',
                }
            },
            handleGroupForm() {
                this.data.form_type === 'create'
                    ? this.addGroup()
                    : this.editGroup()
            },
            addGroup() {
                let self = this

                this.utilities.saving = true

                this.utilities.errors = null

                this.utilities.deleteErrors = null

                axios.post('/api/custom-field-groups/', this.data.current_group)
                    .then(function (response) {
                        self.resetGroup()

                        self.data.groups = response.data

                        self.hideGroupModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            editGroup() {
                let self = this

                this.utilities.saving = true

                this.utilities.errors = null

                this.utilities.deleteErrors = null

                axios.patch('/api/custom-field-groups/' + this.data.current_group.id, this.data.current_group)
                    .then(function (response) {
                        self.resetGroup()

                        self.data.groups = response.data

                        self.getSelectedGroups()

                        self.hideGroupModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            deleteGroup(group) {
                let self = this

                this.utilities.saving = true

                this.utilities.errors = null

                this.utilities.deleteErrors = null

                self.data.current_group = group

                axios.delete('/api/custom-field-groups/' + self.data.current_group.id)
                    .then(function (response) {
                        self.resetGroup()

                        self.data.groups = response.data

                        self.getSelectedGroups()

                        self.hideGroupModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.deleteErrors = error.response.data.errors
                        }
                    })
            },

            showFieldModal(group) {
                this.data.current_group = group

                this.data.form_type = 'create'

                this.utilities.errors = null

                this.resetField()

                this.$modal.show('field-modal')
            },
            showEditFieldModal(group, field) {
                let self = this

                self.data.form_type = 'edit'

                self.utilities.errors = null

                self.utilities.deleteErrors = null

                self.data.current_group = group

                self.data.current_field = {
                    id: field.id,
                    name: field.name,
                    placeholder: field.placeholder,
                    default: field.default,
                    type: collect(this.utilities.types).firstWhere('value', field.type),
                    options: this.implode(this.jsonToArray(field.options), null),
                    bespoke_form_field_id: collect(this.utilities.types).firstWhere('id', field.bespoke_form_field_id),
                    validation_rules: [],
                    validation_args: [],
                }

                collect(self.rules).each(function (rule) {
                    self.data.current_field.validation_args.push(undefined)
                })

                collect(field.rules).each(function (rule) {
                    self.data.current_field.validation_rules.push(rule.id)

                    self.data.current_field.validation_args[rule.id] = rule.pivot.argument
                })

                this.$modal.show('field-modal')
            },
            hideFieldModal() {
                this.$modal.hide('field-modal')
            },
            resetField() {
                this.utilities.saving = false

                this.data.current_field = {
                    id: '',
                    name: '',
                    placeholder: '',
                    default: '',
                    options: '',
                    bespoke_form_field_id: null,
                    validation_rules: [],
                    validation_args: [],
                }
            },
            handleFieldForm() {
                this.data.form_type === 'create'
                    ? this.addField()
                    : this.editField()
            },
            addField() {
                let self = this

                self.utilities.saving = true

                self.utilities.errors = null

                self.utilities.deleteErrors = null

                axios.post('/api/custom-field-groups/' + this.data.current_group.id + '/custom-fields/', this.data.current_field)
                    .then(function (response) {
                        self.resetField()

                        self.data.groups = response.data

                        self.getSelectedGroups()

                        self.hideFieldModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            editField() {
                let self = this

                self.utilities.saving = true

                self.utilities.errors = null

                self.utilities.deleteErrors = null

                axios.patch('/api/custom-field-groups/' + this.data.current_group.id + '/custom-fields/' + this.data.current_field.id, this.data.current_field)
                    .then(function (response) {
                        self.resetField()

                        self.data.groups = response.data

                        self.getSelectedGroups()

                        self.hideFieldModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            deleteField(group, field) {
                let self = this

                self.data.current_group = group

                self.data.current_field = field

                self.utilities.errors = null

                self.utilities.deleteErrors = null

                self.utilities.saving = true

                axios.delete('/api/custom-field-groups/' + this.data.current_group.id + '/custom-fields/' + self.data.current_field.id)
                    .then(function (response) {
                        self.resetField()

                        self.data.groups = response.data

                        self.getSelectedGroups()

                        self.hideFieldModal()
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.saving = false

                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            setBespokeField(type) {
                this.data.current_field.validation_rules = []

                this.data.current_field.validation_args = []

                this.data.current_field.bespoke_form_field_id = type
            },
            isBespokeField(type) {
                let field = this.data.current_field.bespoke_form_field_id

                return {
                    'u-bg-facebook u-text-white': field && field.id === type.id,
                    'u-bg-light-gray': !field || field.id !== type.id,
                }
            },

            getSelectedGroups() {
                let self = this

                axios.post('/api/campaigns/' + this.campaign.id + '/selected-custom-groups')
                    .then(function (response) {
                        self.data.selected_groups = response.data
                    })
            },
            updateSelectedGroups() {
                let self = this

                axios.post('/api/campaigns/' + this.campaign.id + '/selected-custom-groups/update', {'groups': self.data.selected_groups})
                    .then(function (response) {
                        self.data.selected_groups = response.data
                    })
            },
            addSelectedGroup() {
                let self = this

                axios.post('/api/campaigns/' + this.campaign.id + '/selected-custom-groups/store', {'group': self.data.group_to_add})
                    .then(function (response) {
                        self.data.group_to_add = null

                        self.data.selected_groups = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })

            },
            deselectGroup(selectedGroup) {
                let self = this

                axios.delete('/api/campaigns/' + this.campaign.id + '/selected-custom-groups/' + selectedGroup.id)
                    .then(function (response) {
                        let index = self.data.selected_groups.indexOf(selectedGroup)

                        self.data.selected_groups.splice(index, 1)
                    })
            },

            hasRule(rule) {
                return collect(this.data.current_field.validation_rules).contains(rule)
            },
            jsonToArray: function (value) {
                return JSON.parse(value)
            },
            implode: function (array, key = 'name') {
                return collect(array)
                    .map(function (value) {
                        if (key) {
                            return value[key]
                        }

                        return value
                    })
                    .implode(', ')
            },
        },
        mounted() {
            let self = this

            self.data.groups = self.groups

            axios.post('/api/custom-field-types/')
                .then(function (response) {
                    self.utilities.types = response.data
                })

            this.getSelectedGroups()
        },
    }
</script>

