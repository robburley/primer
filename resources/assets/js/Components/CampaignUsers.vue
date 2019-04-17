<style>
</style>

<template>
    <div>
        <div class="row u-mb-small">
            <div class="col-xl-12">
                <a class="c-btn c-btn--blue u-ml-small pull-right" @click.prevent="addUser">
                    Select User
                </a>

                <v-select class="pull-right" maxHeight="200px" v-model="data.user_to_add" :options="unselectedUsers"></v-select>

            </div>
        </div>

        <div class="row u-mb-small">
            <div class="col-xl-12">

                <div v-for="error in utilities.errors">
                    <small class="c-field__message u-color-danger">
                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                    </small>
                </div>
            </div>
        </div>

        <div class="row u-mb-small">
            <table class="c-table"
            >
                <thead class="c-table__head c-table__head--slim">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head u-width-30">
                            Name
                        </th>

                        <th class="c-table__cell c-table__cell--head centered">
                            Supervise <span class="u-hidden-down@wide">Campaign</span>
                        </th>

                        <th class="c-table__cell c-table__cell--head centered">
                            Create <span class="u-hidden-down@wide">Leads</span>
                        </th>

                        <th class="c-table__cell c-table__cell--head centered">
                            Update <span class="u-hidden-down@wide">Leads</span>
                        </th>

                        <th class="c-table__cell c-table__cell--head centered">
                            Confirm <span class="u-hidden-down@wide">Leads</span>
                        </th>

                        <th class="c-table__cell c-table__cell--head"></th>
                    </tr>
                </thead>

                <tr class="c-table__row" v-for="user in data.selectedUsers" :key="user.id" v-if="data.selectedUsers.length > 0">
                    <td class="c-table__cell">
                        {{ user.first_name }} {{ user.last_name }}
                    </td>

                    <td class="c-table__cell text-center centered">
                        <input type="checkbox" v-model="user.pivot.supervisor" @change="updateUser(user)">
                    </td>

                    <td class="c-table__cell text-center centered">
                        <input type="checkbox" v-model="user.pivot.create_new_lead" @change="updateUser(user)">
                    </td>

                    <td class="c-table__cell text-center centered">
                        <input type="checkbox" v-model="user.pivot.update_lead" @change="updateUser(user)">
                    </td>

                    <td class="c-table__cell text-center centered">
                        <input type="checkbox" v-model="user.pivot.confirm_lead" @change="updateUser(user)">
                    </td>

                    <td class="c-table__cell u-pl-zero u-pr-small">
                        <a class="pull-right" href="#!" @click="deleteUser(user)">
                            <i class="fa fa-close u-text-danger"
                               v-tooltip.right-end="{ content: 'Deselect User', classes: 'tooltip' }"
                            ></i>
                        </a>
                    </td>
                </tr>

                <tr class="c-table__row" v-if="data.selectedUsers.length === 0">
                    <td class="c-table__cell text-center centered" colspan="5">
                        No Users Selected
                    </td>
                </tr>
            </table>
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
            users: {
                type: Array,
                required: true,
            },
            selected: {
                type: Array,
                required: true,
            },
        },
        filters: {},
        computed: {
            unselectedUsers() {
                let self = this

                return collect(self.users)
                    .filter(function (user) {
                        return !collect(self.data.selectedUsers).pluck('id').contains(user.id)
                    })
                    .map(function (user) {
                        return {
                            label: user.first_name + ' ' + user.last_name,
                            value: user.id,
                        }
                    })
                    .toArray()
            },
        },
        data() {
            return {
                data: {
                    user_to_add: null,
                    selectedUsers: [],
                },
                utilities: {
                    errors: null,
                },
            }
        },
        methods: {
            addUser() {
                let self = this

                axios.post('/api/campaigns/' + this.campaign.id + '/users', {'user': self.data.user_to_add})
                    .then(function (response) {
                        self.data.user_to_add = null

                        self.data.selectedUsers = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })

            },
            updateUser(user) {
                let self = this

                axios.post('/api/campaigns/' + this.campaign.id + '/users/' + user.id, {'user': user})
                    .then(function (response) {
                        self.data.selectedUsers = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            deleteUser(user) {
                let self = this

                axios.delete('/api/campaigns/' + this.campaign.id + '/users/' + user.id)
                    .then(function (response) {
                        self.data.selectedUsers = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
        },
        mounted() {
            this.data.selectedUsers = this.selected
        },
    }
</script>

