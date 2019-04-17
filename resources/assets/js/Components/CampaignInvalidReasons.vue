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

    .clickable {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div class="row">
            <div class="col-12">
                <a class="c-btn c-btn--success pull-right"
                   v-if="!data.showNewReason"
                   @click="data.showNewReason = !data.showNewReason"
                >
                    Add New Reason
                </a>

                <div v-for="error in utilities.errors">
                    <small class="c-field__message u-color-danger">
                        <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                    </small>
                </div>

                <form @submit.prevent="saveReason" v-if="data.showNewReason">
                    <div class="c-field">
                        <label class="c-field__label">Title</label>

                        <input class="c-input" type="text" placeholder="Invalid Reason Title" v-model="data.new_reason.title">
                    </div>

                    <div class="c-field u-mt-small">
                        <label class="c-field__label">Description</label>

                        <input class="c-input" type="text" placeholder="Invalid Reason Description" v-model="data.new_reason.description">
                    </div>

                    <button type="submit"
                            class="c-btn c-btn--success pull-right u-mt-small"
                    >
                        Add Reason
                    </button>
                </form>
            </div>
        </div>

        <div class="row u-mt-small">
            <div class="col-12">
                <table class="c-table u-border-zero">
                    <thead class="c-table__head">
                        <tr class="c-table__row">
                            <th class="c-table__cell c-table__cell--head">Title</th>
                            <th class="c-table__cell c-table__cell--head">Description</th>
                            <th class="c-table__cell c-table__cell--head"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="c-table__row" v-for="reason in data.reasons">
                            <td class="c-table__cell">{{ reason.title }}</td>
                            <td class="c-table__cell">{{ reason.description}}</td>
                            <td class="c-table__cell">
                                <i class="fa fa-close u-color-danger pull-right clickable" @click="removeReason(reason)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
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
            initial_reasons: {
                required: true,
                type: Array,
            },
        },
        filters: {},
        computed: {},
        data() {
            return {
                data: {
                    reasons: [],
                    new_reason: {
                        title: '',
                        description: '',
                    },
                    showNewReason: false,
                    baseUrl: '/api/campaigns/' + this.campaign.id + '/invalid-reasons/',
                },
                utilities: {
                    errors: null,
                },
            }
        },
        methods: {
            saveReason() {
                let self = this

                self.utilities.errors = null

                axios.post(self.data.baseUrl, self.data.new_reason)
                    .then(function (response) {
                        self.data.reasons = response.data

                        self.data.new_reason.title = ''
                        self.data.new_reason.description = ''

                        this.data.showNewReason = !this.data.showNewReason
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            removeReason(reason) {
                let self = this

                self.utilities.errors = null

                axios.delete(self.data.baseUrl + reason.id)
                    .then(function (response) {
                        self.data.reasons = response.data
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
        },
        mounted() {
            this.data.reasons = this.initial_reasons
        },
    }
</script>

