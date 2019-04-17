<style>
    .clickable {
        cursor: pointer;
    }

    .movable {
        cursor: grab;
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
                <a class="c-btn c-btn--blue u-ml-small pull-right" @click.prevent="addSelectedGroup">
                    Select Group
                </a>

                <v-select class="pull-right" maxHeight="200px" v-model="data.group_to_add" :options="unselectedGroups"></v-select>
            </div>
        </div>

        <div class="row u-mb-small">
            <div class="col-sm-12">
                <draggable v-if="data.selected_groups.length > 0" v-model="data.selected_groups"
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
                                    <i class="fa fa-sort movable"></i>

                                    {{ selectedGroup.custom_field_group.name }}
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

                                <th class="c-table__cell c-table__cell--head centered">
                                    <span class="u-hidden-down@wide">Show to </span>Customer
                                </th>

                                <th class="c-table__cell c-table__cell--head centered">
                                    <span class="u-hidden-down@wide">Required for </span>Completion
                                </th>

                                <th class="c-table__cell c-table__cell--head centered">
                                    <span class="u-hidden-down@wide">Show to </span>Confirmer
                                </th>
                            </tr>
                        </thead>

                        <draggable v-model=" selectedGroup.selected_custom_fields"
                                   :options="{group: {name: 'fields'}}"
                                   :element="'tbody'"
                                   @end="updateSelectedGroups"
                        >
                            <tr class="c-table__row" v-for="field in selectedGroup.selected_custom_fields">
                                <td class="c-table__cell">
                                    <i class="fa fa-sort movable p-small"></i>

                                    {{ field.custom_field.name }}

                                    <span class="u-text-mute">
                                        | <small>{{ field.custom_field.bespoke_form_field.name }}</small>
                                    </span>
                                </td>

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.show_to_lead_generator" @change="updateSelectedGroups">
                                </td>

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.show_to_customers" @change="updateSelectedGroups">
                                </td>

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.required_for_completion" @change="updateSelectedGroups">
                                </td>

                                <td class="c-table__cell text-center centered">
                                    <input type="checkbox" v-model="field.show_to_confirmer" @change="updateSelectedGroups">
                                </td>
                            </tr>
                        </draggable>
                    </table>
                </draggable>

                <div v-else>
                    <h4>Please Select a Custom Field Group</h4>
                </div>
            </div>
        </div>
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
                    selected_groups: [],
                    group_to_add: null,
                },
                utilities: {
                    errors: null,
                },
            }
        },
        methods: {
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
        },
        mounted() {
            let self = this

            self.data.groups = self.groups

            this.getSelectedGroups()
        },
    }
</script>

