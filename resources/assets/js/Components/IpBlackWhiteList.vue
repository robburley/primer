<style scoped>
    .clickable {
        cursor: pointer;
    }
</style>

<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="u-width-100 u-flex">
                <div class="u-p-small"
                     :class="{'u-bg-info u-text-white': data.list === 1, 'u-bg-light-gray u-text-mute clickable' : data.list === 0}"
                     @click="toggle(1)"
                >
                    Black List
                </div>

                <div class="u-p-small"
                     :class="{'u-bg-info u-text-white': data.list === 0, 'u-bg-light-gray u-text-mute clickable' : data.list === 1}"
                     @click="toggle(0)"
                >
                    White List
                </div>
            </div>

            <div class="u-width-100 u-flex u-flex-wrap u-pt-small bg">
                <div v-for="(ip, index) in (data.list === 1 ? data.blacklist : data.whitelist)" class="u-ph-small u-bg-light-gray u-mr-small u-mb-small">
                    {{ ip.address }}

                    <i class="fa fa-close u-text-danger u-ml-small u-pv-xsmall clickable" @click="remove(index, ip)"></i>
                </div>

                <div class="u-mr-small u-mb-small">
                    <div class="c-field has-addon-right">
                        <input class="c-input" type="text" placeholder="123.123.123.123" v-model="data.new">

                        <span class="c-field__addon clickable" @click="add">
                            <i class="fa fa-check u-text-success"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div v-for="error in utilities.errors">
                <small class="c-field__message u-color-danger">
                    <i class="fa fa-times-circle"></i> {{ error[0] }} <br>
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {},
        props: {
            blacklist: {
                type: Array,
                required: true,
            },
            whitelist: {
                type: Array,
                required: true,
            },
            type: {
                type: Number,
                required: true,
            },
        },
        filters: {},
        computed: {
            currentList() {
                return this.data.list === 1
                    ? 'blacklist'
                    : 'whitelist'
            },
        },
        data() {
            return {
                data: {
                    new: '',
                    list: 1,
                    blacklist: [],
                    whitelist: [],
                },
                utilities: {
                    errors: null,
                },
            }
        },
        methods: {
            toggle(type) {
                let self = this

                axios.post('/api/ip-restriction-type', {
                    'type': type,
                })
                    .then(function (response) {
                        self.data.list = type
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            remove(index, ip) {
                let self = this

                self.utilities.errors = null

                axios.delete('/api/ip-restrictions/' + ip.id)
                    .then(function (response) {
                        self.data[self.currentList].splice(index, 1)
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            self.utilities.errors = error.response.data.errors
                        }
                    })
            },
            add() {
                let self = this

                self.utilities.errors = null

                if (this.data.new) {
                    axios.post('/api/ip-restrictions', {
                        'address': self.data.new,
                        'blacklisted': self.data.list,
                    })
                        .then(function (response) {
                            self.data[self.currentList].push(response.data)

                            self.data.new = ''
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
            this.data.blacklist = this.blacklist

            this.data.whitelist = this.whitelist

            this.data.list = this.type
        },
    }
</script>

